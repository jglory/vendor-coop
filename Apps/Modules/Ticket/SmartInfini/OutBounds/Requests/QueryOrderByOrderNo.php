<?php
namespace Apps\Modules\Ticket\SmartInfini\OutBounds\Requests;


use Apps\Libraries\Log;

use Apps\Libraries\ApiClient\Request;
use Apps\Libraries\ApiClient\ResponseInterface;

use Phalconry\Aggregates\Ticket\Order as OrderModel;

use Apps\Modules\Ticket\SmartInfini\Config;

use Apps\Modules\Ticket\SmartInfini\OutBounds\Responses\QueryOrderByOrderNo as QueryOrderByOrderNoResponse;

class QueryOrderByOrderNo extends Request
{
    const URL = Config::URL_QUERYORDERBYORDERNO;
    const TOKEN = Config::TOKEN;

    /**
     * @var OrderModel $orderModel
     */
    private $orderModel;

    /**
     * @inheritDoc
     */
    protected function createResponse(string $responseText): ResponseInterface
    {
        return new QueryOrderByOrderNoResponse($responseText);
    }

    /**
     * 생성 시 호출되는 초기화 함수. 생성자에 전달되는 $data를 기초로 Request 시에 전송할 body 데이터를 만드는 기능으로 의도되었다.
     * @return array
     */
    protected function initialize()
    {
        $data = $this->getData();

        $this->orderModel = $data['orderModel'];

        $this->body = json_encode(['orderNum' => $this->orderModel->getOrderMaster()->getOrderNo()], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @inheritDoc
     */
    public function getBody(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return self::METHOD_GET;
    }

    /**
     * @inheritDoc
     */
    public function getOptions(): array
    {
        return [
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json; charset=UTF-8',
                'Authorization: bearer '.self::TOKEN,
                'Content-Length: ' . strlen($this->getBody()),
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function getUrl(): string
    {
        return str_replace('{orderNum}', $this->orderModel->getOrderMaster()->getOrderNo(), self::URL);
    }
}