<?php
namespace Phalconry\Services\Service\WMPOApiClient\Requests;


use Apps\Libraries\Log;

use Apps\Libraries\ApiClient\Request;
use Apps\Libraries\ApiClient\ResponseInterface;

use Apps\Vendors\Model as VendorModel;

use Phalconry\Services\Service\WMPOApiClient\Responses\UseTicket as UseTicketResponse;

class UseTicket extends Request
{
    const URL = HOST_CUPPING_API_USETICKET_URL;
    const TOKEN = HOST_CUPPING_API_TOKEN;

    /**
     * @var VendorModel $orderModel
     */
    private $orderModel;
    /**
     * @var string $ticketCode
     */
    private $ticketCode;
    /**
     * @var int $storeBranchID
     */
    private $storeBranchID;

    /**
     * @var string $body;
     */
    private $body;

    /**
     * @inheritDoc
     */
    protected function createResponse(string $responseText): ResponseInterface
    {
        return new UseTicketResponse($responseText);
    }

    /**
     * 생성 시 호출되는 초기화 함수. 생성자에 전달되는 $data를 기초로 Request 시에 전송할 body 데이터를 만드는 기능으로 의도되었다.
     * @return array
     */
    protected function initialize()
    {
        $data = $this->getData();

        $this->orderModel = $data['orderModel'];
        $this->ticketCode = $data['ticketCode'];
        $this->storeBranchID = $data['storeBranchID'];

        $this->body = json_encode(['storeBranchID' => $this->storeBranchID], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @inheritDoc
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return self::METHOD_POST;
    }

    /**
     * @inheritDoc
     */
    public function getOptions(): array
    {
        return [
            CURLOPT_HTTPHEADER => [
                'token: ' . self::TOKEN,
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function getUrl(): string
    {
        $url = str_replace('{memberID}', $this->orderModel->getMemberID(), self::URL);
        $url = str_replace('{orderNo}', $this->orderModel->getOrderMaster()->getOrderNo(), $url);
        $url = str_replace('{ticketCode}', $this->ticketCode, $url);
        return $url;
    }
}