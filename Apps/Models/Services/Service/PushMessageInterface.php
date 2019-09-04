<?php
namespace Phalconry\Services\Service;


use Phalconry\Aggregates\Delivery\ExternalVendor as ExternalVendorModel;
use Phalconry\Aggregates\Delivery\Order as OrderModel;
use Phalconry\Aggregates\Delivery\Store as StoreModel;

interface PushMessageInterface
{
    const TYPE_FRONT    = 1;
    const TYPE_BIZ      = 2;

    const CANCEL_CODE_SOLDOUT = 10;
    const CANCEL_CODE_DELAYED_COMPLETION = 20;
    const CANCEL_CODE_CUSTOMER_REQUEST = 30;
    const CANCEL_CODE_ETC = 40;
    const CANCEL_CODE_UNDELIVERABLE_AREA = 50;
    const CANCEL_CODE_CUSTOMER_UNKNOWN = 60;

    const CANCEL_MESSAGES = [
        self::CANCEL_CODE_SOLDOUT => '[주문취소] 메뉴 또는 옵션이 품절되어 주문이 취소되었습니다.',
        self::CANCEL_CODE_DELAYED_COMPLETION => '[주문취소] 제조지연으로 주문이 취소되었습니다.',
        self::CANCEL_CODE_CUSTOMER_REQUEST => '[주문취소] 고객요청으로 주문이 취소되었습니다.',
        self::CANCEL_CODE_ETC => '[주문취소] 기타 사유로 주문이 취소되었습니다.',
        self::CANCEL_CODE_UNDELIVERABLE_AREA => '[주문취소] 배달불가능지역으로 주문이 취소되었습니다.',
        self::CANCEL_CODE_CUSTOMER_UNKNOWN => '[주문취소] 고객정보 부정확으로 주문이 취소되었습니다.',
    ];

    const CONFIRM_CODE_ARRIVAL_NOTICE = 1;
    const CONFIRM_CODE_COMPLETION_NOTICE = 2;

    const CONFIRM_MESSAGES = [
        self::CONFIRM_CODE_ARRIVAL_NOTICE => '[제조중] {storeName}에서 주문하신 메뉴가 {deliveryTime}분 후에 도착할 예정입니다.',
        self::CONFIRM_CODE_COMPLETION_NOTICE => '[제조중] {storeName}에서 주문하신 메뉴가 {deliveryTime}분 후 완성될 예정입니다.',
    ];

    const CONFIRMOVERTIME_CODE_ARRIVAL_NOTICE = 1;
    const CONFIRMOVERTIME_CODE_COMPLETION_NOTICE = 2;

    const CONFIRMOVERTIME_MESSAGES = [ // WMPO에서만 특수하게 사용
        self::CONFIRMOVERTIME_CODE_ARRIVAL_NOTICE => '[제조중] {storeName}에서 주문하신 메뉴가 2시간 이상 후에 도착할 예정입니다.',
        self::CONFIRMOVERTIME_CODE_COMPLETION_NOTICE => '[제조중] {storeName}에서 주문하신 메뉴가 2시간 이상 후 완성될 예정입니다.',
    ];

    const COMPLETE_CODE_ARRIVED = 1;
    const COMPLETE_CODE_COMPLETED = 2;

    const COMPLETE_MESSAGES = [
        self::COMPLETE_CODE_ARRIVED => '[배달완료] {storeName}에서 주문하신 메뉴가 배달완료 되었습니다. 맛있게 드세요!',
        self::COMPLETE_CODE_COMPLETED => '[메뉴완성] {storeName}에서 주문메뉴가 완성되었습니다.',
    ];

    /**
     * User PushMessage 만들기
     *
     * @param ExternalVendorModel $externalVendorModel
     * @param OrderModel $orderModel
     * @param StoreModel $storeModel
     * @param string $status
     * @param int $deliveryTime
     * @param int $cancelCode
     * @return string
     */
    public function makePushMessage(ExternalVendorModel $externalVendorModel, OrderModel $orderModel, StoreModel $storeModel, $status, $deliveryTime, $cancelCode);

    /**
     * 구매자에게 푸쉬 메세지를 보낸다.
     * @param int $type
     * @param string $deviceID
     * @param string $title
     * @param string $message
     * @param int $orderNo
     * @return mixed
     */
    public function send(int $type, string $deviceID, string $title, string $message, int $orderNo);
}