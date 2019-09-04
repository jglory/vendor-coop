<?php
namespace Apps\Modules\Delivery\Foodtech\OutBounds;


use Apps\Common\Constants\ExternalCode;

use Apps\Libraries\Log;

use Apps\Modules\Delivery\Foodtech\Config;
use Apps\Modules\Delivery\Foodtech\OutBound;

use Apps\Libraries\EntityProxy;

use Apps\Modules\Exception as VendorException;

use Phalconry\Aggregates\Delivery\ExternalVendor as ExternalVendorModel;
use Phalconry\Aggregates\Delivery\Order as OrderModel;
use Phalconry\Aggregates\Delivery\Store as StoreModel;

use Apps\Modules\Delivery\Foodtech\OutBounds\Requests\CancelOrderByOrderNo as CancelOrderByOrderNoRequest;
use Apps\Modules\Delivery\Foodtech\OutBounds\Responses\CancelOrderByOrderNo as CancelOrderByOrderNoResponse;

use Phalconry\Entities\ExternalOrderFoodtech as ExternalOrderFoodtechEntity;
use Phalconry\Entities\StoreReception as StoreReceptionEntity;
use Phalconry\Entities\StoreReceptionHistory as StoreReceptionHistoryEntity;

use Phalconry\Services\Service\WMPOApiClient\Responses\CancelOrder as WMPOApiCancelOrderResponse;

class CancelOrderByOrderNo extends OutBound
{
    /**
     * 상점 아이디
     * @var int $storeID
     */
    private $storeID;
    /**
     * 주문 번호
     * @var int $orderNo
     */
    private $orderNo;

    /**
     * 자사에 주문 취소를 요청한다.
     * @param OrderModel $orderModel
     * @return WMPOApiCancelOrderResponse
     * @throws VendorException
     */
    private function sendCancelOrderToWMPORequest(OrderModel $orderModel)
    {
        return $this->wmpoApiClient->sendCancelOrder($orderModel);
    }


    /**
     * @param array|\JsonSerializable $data
     * @return \Apps\Libraries\ApiClient\ResponseInterface|CancelOrderByOrderNoResponse
     */
    protected function sendRequestToVendor($data)
    {
        return (new CancelOrderByOrderNoRequest($data))->request();
    }

    /**
     * CancelOrderByOrderNo constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->storeID = $this->dispatcher->getParam('storeID');
        $this->orderNo = $this->dispatcher->getParam('orderNo');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function process()
    {
        /**
         * @var OrderModel $orderModel
         * @var StoreModel $storeModel
         * @var ExternalVendorModel $externalVendorModel
         */
        $orderModel = $this->vendorService->getOrderModelByVendorCodeStoreIDOrderNo($this->getVendorCode(), $this->storeID, $this->orderNo);
        $storeModel = $this->vendorService->getStoreModelByStoreID($this->storeID);

        // 취소처리
        $this->sendCancelOrderToWMPORequest($orderModel);

        // 상태갱신 - StoreReception
        $orderModel->getStoreReception()->setStatus(StoreReceptionEntity::STATUS_REJECT, time());
        $storeReceptionHistoryProxy = new EntityProxy(
            StoreReceptionHistoryEntity::copyDataFromStoreReception($orderModel->getStoreReception(), new StoreReceptionHistoryEntity())
        );
        $orderModel->getStoreReceptionHistoriesRef()[] = $storeReceptionHistoryProxy;

        $orderUpdateModel = (new OrderModel())->setStoreReception($orderModel->getStoreReception());
        $orderInsertModel = (new OrderModel())->setStoreReceptionHistories([$storeReceptionHistoryProxy]);

        $externalOrderFoodtech = ExternalOrderFoodtechEntity::findFirstByOrderNo($orderModel->getOrderMaster()->getOrderNo());
        $externalOrderFoodtechProxy = ($externalOrderFoodtech===false ? null : new EntityProxy($externalOrderFoodtech));
        $data = [
            'orderModel' => $orderModel,
            'storeModel' => $storeModel,
            'externalOrderFoodtechProxy' => $externalOrderFoodtechProxy,
            'storeReceptionHistory' => $storeReceptionHistoryProxy,
        ];

        try {
            $response = $this->sendRequestToVendor($data);
            $responseText = $response->getResponseText();
        } catch (\Exception $e) {
            // 실패 해도 무시한다!
        }

        try {
            $transaction = $this->transactionsManager->get();
            $this->vendorService->update($orderUpdateModel, $transaction);
            $this->vendorService->insert($orderInsertModel, $transaction);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback($e->getMessage());
        }

        $this->fireProcessedEvent($data);
    }
}