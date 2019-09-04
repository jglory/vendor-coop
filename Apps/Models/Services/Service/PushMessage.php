<?php
namespace Phalconry\Services\Service;


use Apps\Common\Constants\ExternalCode;

use Apps\Libraries\Curl;
use Apps\Libraries\Log;

use Phalconry\Aggregates\Delivery\ExternalVendor as ExternalVendorModel;
use Phalconry\Aggregates\Delivery\Order as OrderModel;
use Phalconry\Aggregates\Delivery\Store as StoreModel;

use Apps\Vendors\Deliveries\Modules\WMPO\Config as WMPOConfig;

class PushMessage implements PushMessageInterface
{
    /**
     * @inheritDoc
     */
    public function makePushMessage(ExternalVendorModel $externalVendorModel, OrderModel $orderModel, StoreModel $storeModel, $status, $deliveryTime, $cancelCode)
    {
        switch ( $status ) {
            case 'CONFIRM' : // 제조중
                if ($externalVendorModel->getExternalVendor()->getCode() == WMPOConfig::VENDOR_CODE && $deliveryTime >= 120 ) {
                    $message = self::CONFIRMOVERTIME_MESSAGES[$orderModel->getOrderDelivery()->getType()];
                } else {
                    $message = self::CONFIRM_MESSAGES[$orderModel->getOrderDelivery()->getType()];
                }
                break;
            case 'COMPLETE' : // 완료
                $message = self::COMPLETE_MESSAGES[$orderModel->getOrderDelivery()->getType()];
                break;

            case 'CANCEL' : // 주문취소
                $message = self::CANCEL_MESSAGES[$cancelCode];
                break;

            default :
                $message = '';
        }
        return str_replace(['{storeName}','{deliveryTime}'], [$storeModel->getStore()->getName(), $deliveryTime], $message);
    }

    /**
     * @inheritDoc
     */
    public function send(int $type, string $deviceID, string $title, string $message, int $orderNo)
    {
        $pushData = [
            'type'      => $type, // FRONT
            'deviceId'  => $deviceID,
            'title'     => $title,
            'message'   => $message,
            'orderNo'   => $orderNo,
        ];

        $options = [
            CURLOPT_HTTPHEADER => [
                'token: ' . WMPOConfig::TOKEN,
            ]
        ];

        // 내부 api 연동
        $response = Curl::post('', http_build_query($pushData), $options);
    }
}