<?php
namespace Apps\Modules\Ticket\SmartInfini\OutBounds;


use Apps\Libraries\Curl;
use Apps\Libraries\Log;

use Phalconry\Aggregates\Ticket\ExternalVendor as ExternalVendorModel;
use Phalconry\Aggregates\Ticket\Member as MemberModel;
use Phalconry\Aggregates\Ticket\Order as OrderModel;
use Phalconry\Aggregates\Ticket\Store as StoreModel;

use Apps\Modules\Exception as VendorException;

use Apps\Modules\Ticket\SmartInfini\Config;
use Apps\Modules\Ticket\SmartInfini\OutBound;

use Apps\Modules\Ticket\SmartInfini\OutBounds\Requests\CancelOrderByOrderNo as CancelOrderByOrderNoRequest;
use Apps\Modules\Ticket\SmartInfini\OutBounds\Responses\CancelOrderByOrderNo as CancelOrderByOrderNoResponse;

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
     * 벤더사로 보내는 주문 취소 요청을 처리한다.
     * @throws \Exception
     */
    public function process()
    {
        $orderModel = $this->vendorService->getOrderModelByVendorCodeStoreIDOrderNo($this->getVendorCode(), $this->storeID, $this->orderNo);
        $storeModel = $this->vendorService->getStoreModelByStoreID($this->storeID);

        $this->sendCancelOrderToWMPORequest($orderModel);

        $response = $this->sendRequestToVendor(['orderModel' => $orderModel]);
        $responseData = $response->getResponseData();

        $this->fireProcessedEvent([
            'orderModel' => $orderModel,
            'storeModel' => $storeModel
        ]);
    }
}