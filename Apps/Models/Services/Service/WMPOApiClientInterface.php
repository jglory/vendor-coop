<?php
namespace Phalconry\Services\Service;


use Apps\Modules\Exception as VendorException;
use Phalconry\Aggregates\Aggregate as VendorModel;
use Phalconry\Services\Service\WMPOApiClient\Responses\CancelOrder as CancelOrderResponse;

interface WMPOApiClientInterface
{
    /**
     * WMPO API 서버로 주문 취소 요청을 보낸다.
     *
     * @param VendorModel $orderModel
     * @return CancelOrderResponse
     * @throws VendorException
     */
    public function sendCancelOrder(VendorModel $orderModel);
}