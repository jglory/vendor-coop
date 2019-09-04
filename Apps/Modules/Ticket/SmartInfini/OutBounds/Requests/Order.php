<?php
namespace Apps\Modules\Ticket\SmartInfini\OutBounds\Requests;


use Apps\Libraries\Crypt;
use Apps\Libraries\Curl;
use Apps\Libraries\Log;

use Apps\Libraries\ApiClient\Request;
use Apps\Libraries\ApiClient\ResponseInterface;

use Phalconry\Aggregates\Ticket\ExternalProduct as ExternalProductModel;
use Phalconry\Aggregates\Ticket\Member as MemberModel;
use Phalconry\Aggregates\Ticket\Order as OrderModel;

use Apps\Modules\Ticket\SmartInfini\Config;

use Apps\Modules\Ticket\SmartInfini\OutBounds\Responses\Order as OrderResponse;

/**
 * 주문 요청 객체 클래스
 *
 * POST https://external.smartinfini.com/v1/test/reseller/790/process
 * {
 *     "orderNum":"2019070500599487",
 *     "buyerName":"박상기",
 *     "buyerHp":"01088921200",
 *     "buyDate":"2019-07-05 12:17:08",
 *     "orderInfo":[
 *         {
 *             "prdCd":"P201703288678",
 *             "opt1":1,
 *             "opt2":1,
 *             "opt3":1,
 *             "opt4":1,
 *             "validStart":"2019-07-05 00:00:00",
 *             "validEnd":"2019-07-05 23:59:59"
 *         }
 *     ]
 * }
 *
 * Class Order
 * @package Apps\Modules\Ticket\SmartInfini\OutBounds\Requests
 */
class Order extends Request
{
    const URL = Config::URL_ORDER;
    const TOKEN = Config::TOKEN;

    /**
     * @var string $body
     */
    private $body;

    /**
     * @var ExternalProductModel $externalProductModels
     */
    private $externalProductModels;

    /**
     * @var MemberModel $memberModel
     */
    private $memberModel;

    /**
     * @var OrderModel $orderModel
     */
    private $orderModel;

    /**
     * @inheritDoc
     */
    protected function createResponse(string $responseText): ResponseInterface
    {
        return new OrderResponse($responseText);
    }

    /**
     * 생성 시 호출되는 초기화 함수. 생성자에 전달되는 $data를 기초로 Request 시에 전송할 body 데이터를 만드는 기능으로 의도되었다.
     * @return array
     */
    protected function initialize()
    {
        $data = $this->getData();

        $this->orderModel = $data['orderModel'];
        $this->memberModel = $data['memberModel'];
        $this->externalProductModels = $data['externalProductModels'];

        $this->body = '';

        // 핸드폰 번호 필터링. 핸드폰은 항상 숫자로만 구성되어 들어온다.
        $mobile = $this->memberModel->getMember()->getMobile();
        preg_match('/[^0-9]+/u', $mobile, $matches);
        if (count($matches)>0) {
            $mobile = '';
        }
        $requestData = [
            'orderNum' => $this->orderModel->getOrderMaster()->getOrderNo(),
            'buyerName' => $this->memberModel->getMember()->getName(),
            'buyerHp' => $mobile,
            'buyDate' => $this->orderModel->getOrderMaster()->getCreated(),
            'orderInfo' => []
        ];

        foreach ($this->orderModel->getWalletTickets() as $item) {
            $productCode = '';
            $productID = $item->getProductID();
            $productOptionID = $item->getProductOptionID();

            /** @var ExternalProductModel $externalProductModel */
            foreach ($this->externalProductModels as $externalProductModel) {
                if ($productID == $externalProductModel->getExternalProduct()->getProductID()
                    && $productOptionID == $externalProductModel->getExternalProduct()->getProductOptionID()) {
                    $productCode = $externalProductModel->getExternalProduct()->getMenuCode();
                    break;
                }
            }

            $requestData['orderInfo'][] = [
                'prdCd' => $productCode,
                'opt1' => 1,
                'opt2' => 0,
                'opt3' => 0,
                'opt4' => 0,
                'validStart' => $item->getBeginTime(),
                'validEnd' => $item->getExpireTime()
            ];
        }

        $this->body = json_encode($requestData, JSON_UNESCAPED_UNICODE);
        Log::debug(__METHOD__ . ': ' . $this->body);
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
        return self::URL;
    }
}