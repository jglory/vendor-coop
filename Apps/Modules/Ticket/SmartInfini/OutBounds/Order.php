<?php
namespace Apps\Modules\Ticket\SmartInfini\OutBounds;


use Apps\Libraries\Log;

use Apps\Modules\Exception as VendorException;

use Phalconry\Aggregates\Ticket\ExternalProduct as ExternalProductModel;
use Phalconry\Aggregates\Ticket\Member as MemberModel;
use Phalconry\Aggregates\Ticket\Order as OrderModel;

use Apps\Modules\Ticket\SmartInfini\OutBound;

use Apps\Modules\Ticket\SmartInfini\OutBounds\Requests\Order as OrderRequest;
use Apps\Modules\Ticket\SmartInfini\OutBounds\Responses\Order as OrderResponse;
use Phalconry\Entities\AppsLog;

class Order extends OutBound
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
     * @inheritDoc
     */
    protected function sendRequestToVendor($data)
    {
        return (new OrderRequest($data))->request();
    }

    /**
     * 응답 데이터를 참조하여 연관된 티켓의 코드를 할당하고 데이터베이스에 반영한다.
     * @param OrderModel $orderModel
     * @param MemberModel $memberModel
     * @param array $externalProductModels
     * @param array $responseData
     */
    private function updateTicketCode(OrderModel $orderModel, MemberModel $memberModel, array $externalProductModels, array $responseData)
    {
        $updateModel = new OrderModel();

        $barcodeInfoMap = [];
        foreach ($responseData['barcodeInfo'] as $info) {
            $prdCd = $info['prdCd'];
            if (isset($barcodeInfoMap[$prdCd])===false) {
                $barcodeInfoMap[$prdCd] = [];
            }
            $barcodeInfoMap[$prdCd][] = $info;
        }
        $ticketMap = [];
        foreach ($orderModel->getWalletTicketsRef() as $ticket) {
            $externalProductModel = null;
            foreach ($externalProductModels as $model) {
                if ( $ticket->getProductID()==$model->getExternalProduct()->getProductID()
                    && $ticket->getProductOptionID()==$model->getExternalProduct()->getProductOptionID()) {
                    $externalProductModel = $model;
                    break;
                }
            }
            $prdCd = $externalProductModel->getExternalProduct()->getMenuCode();
            if (isset($ticketMap[$prdCd])===false) {
                $ticketMap[$prdCd] = [];
            }
            $ticketMap[$prdCd][] = $ticket;
        }

        $prdCds = array_keys($ticketMap);
        while (count($prdCds)>0) {
            $prdCd = array_pop($prdCds);
            $tickets = array_pop($ticketMap);
            $infos = $barcodeInfoMap[$prdCd];

            do {
                $ticket = array_pop($tickets);
                $info = array_pop($infos);
                $ticket->setCode($info['mainbarcode']);

                $updateModel->getWalletTicketsRef()[] = $ticket;
            } while (count($tickets)>0);
        }

        return $updateModel;
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
     * 벤더사로 보내는 주문 요청을 처리한다.
     */
    public function process()
    {
        $orderModel = $this->vendorService->getOrderModelByVendorCodeStoreIDOrderNo($this->getVendorCode(), $this->storeID, $this->orderNo);
        $memberModel = $this->vendorService->getMemberModelByMemberID($orderModel->memberID);
        $externalProductModels = $this->vendorService->getExternalProductModelSetByOrderNo($this->orderNo);

        /** @var OrderResponse $response */
        $response = $this->sendRequestToVendor([
            'orderModel' => $orderModel,
            'memberModel' => $memberModel,
            'externalProductModels' => $externalProductModels
        ]);
        $responseData = $response->getResponseData();

        $orderUpdateModel = $this->updateTicketCode($orderModel, $memberModel, $externalProductModels, $responseData);

        try {
            $transaction = $this->transactionsManager->get();
            $this->vendorService->update($orderUpdateModel, $transaction);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback($e->getMessage());
        }

        $this->fireProcessedEvent([
            'orderModel' => $orderModel,
            'memberModel' => $memberModel,
            'externalProductModels' => $externalProductModels,
            'orderUpdateModel' => $orderUpdateModel,
        ]);
    }
}
