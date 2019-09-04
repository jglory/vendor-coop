<?php
namespace Phalconry\Services\Service;


use Apps\Libraries\Log;

use Apps\Modules\Exception as VendorException;

use Phalconry\Aggregates\Aggregate as VendorModel;

use Phalconry\Services\Service\WMPOApiClient\Requests\CancelOrder as CancelOrderRequest;
use Phalconry\Services\Service\WMPOApiClient\Responses\CancelOrder as CancelOrderResponse;
use Phalconry\Services\Service\WMPOApiClient\Requests\UseTicket as UseTicketRequest;
use Phalconry\Services\Service\WMPOApiClient\Responses\UseTicket as UseTicketResponse;

/**
 * Class WMPOApiClient
 * @package Phalconry\Services\Service
 */
class WMPOApiClient implements WMPOApiClientInterface
{
    /**
     * WMPO API 서버로 주문 취소 요청을 보낸다.
     *
     * @param VendorModel $orderModel
     * @return CancelOrderResponse
     * @throws VendorException
     */
    public function sendCancelOrder(VendorModel $orderModel): CancelOrderResponse
    {
        /** @var CancelOrderResponse $response */
        $response = (new CancelOrderRequest(['orderModel' => $orderModel]))->request();
        if ($response->getResponseCode()!==CancelOrderResponse::RESPONSE_SUCCESS_CODE) {
            throw new VendorException(VendorException::ERROR_CODE[923], 923);
        }

        return $response;
    }

    /**
     * WMPO API 서버로 티켓 사용 요청을 보낸다.
     *
     * @param VendorModel $orderModel
     * @return UseTicketResponse
     * @throws VendorException
     */
    public function sendUseTicket(VendorModel $orderModel, string $ticketCode, int $storeBranchID): UseTicketResponse
    {
        /** @var UseTicketResponse $response */
        $response = (new UseTicketRequest(['orderModel' => $orderModel, 'ticketCode' => $ticketCode, 'storeBranchID' => $storeBranchID]))->request();
        if (is_null($response->getResponseCode()) || $response->getResponseCode()!==UseTicketResponse::RESPONSE_SUCCESS_CODE) {
            throw new VendorException($response->getResponseData()['result']['message'], $response->getResponseCode());
        }
        return $response;
    }
}