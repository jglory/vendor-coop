<?php
namespace Phalconry\Services\Service\Delivery\EventListeners\Foodtech;

use Apps\Libraries\Log;

use Phalconry\Aggregates\Delivery\ExternalProduct as ExternalProductModel;
use Phalconry\Aggregates\Delivery\Member as MemberModel;
use Phalconry\Aggregates\Delivery\Order as OrderModel;
use Phalconry\Aggregates\Delivery\Store as StoreModel;
use Phalconry\Services\Service\PushMessage;
use Phalconry\Services\Service\PushMessageInterface as PushMessageServiceInterface;
use Phalconry\Services\Service\Delivery\VendorInterface as VendorDeliveryServiceInterface;

/**
 * Class OutBound
 * @package Phalconry\Services\Service\Delivery\EventListeners\Foodtech
 * @property PushMessageServiceInterface $pushMessageService
 * @property VendorDeliveryServiceInterface $vendorService
 */
class OutBound
{
    /**
     * Foodtech 아웃바운드 주문 완료 이벤트를 처리한다.
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
            '위메프오 배달픽업',
            PushMessageServiceInterface::CANCEL_MESSAGES[PushMessageServiceInterface::CANCEL_CODE_ETC],
            $orderModel->getOrderMaster()->getOrderNo()
        );
    }
}