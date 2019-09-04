<?php
namespace Phalconry\Services\Service\Ticket\EventListeners\SmartInfini;

use Apps\Libraries\Log;

use Phalconry\Aggregates\Ticket\ExternalProduct as ExternalProductModel;
use Phalconry\Aggregates\Ticket\Member as MemberModel;
use Phalconry\Aggregates\Ticket\Order as OrderModel;
use Phalconry\Aggregates\Ticket\Store as StoreModel;
use Phalconry\Services\Service\PushMessageInterface as PushMessageServiceInterface;
use Phalconry\Services\Service\Ticket\VendorInterface as VendorTicketServiceInterface;

/**
 * Class OutBound
 * @package Phalconry\Services\Service\Delivery\EventListeners\SmartInfini
 * @property PushMessageServiceInterface pushMessageService
 * @property VendorTicketServiceInterface vendorService'
 */
class OutBound
{
    /**
     * SmartInfini 아웃바운드 주문 완료 이벤트를 처리한다.
     * @param \Phalcon\Events\Event $event
     * @param object $source
     * @param mixed $data
     */
    public function onProcessedCancelOrderByOrderNo($event, $source, $data)
    {
        Log::debug(__METHOD__);

        /**
         * @var MemberModel $memberModel
         * @var OrderModel $orderModel
         * @var StoreModel $storeModel
         * @var ExternalProductModel[] $externalProductModels
         */
        $orderModel = $data['orderModel'];
        $storeModel = $data['storeModel'];
        $memberModel = $this->vendorService->getMemberModelByMemberID($orderModel->getMemberID());

        $this->pushMessageService->send(
            PushMessageServiceInterface::TYPE_FRONT,
            $memberModel->getToken()->getDeviceId(),
            '위메프오 티켓',
            PushMessageServiceInterface::CANCEL_MESSAGES[PushMessageServiceInterface::CANCELCODE_ETC],
            $orderModel->getOrderMaster()->getOrderNo()
        );
    }
}