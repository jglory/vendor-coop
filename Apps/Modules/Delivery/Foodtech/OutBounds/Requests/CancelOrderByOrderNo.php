<?php
namespace Apps\Modules\Delivery\Foodtech\OutBounds\Requests;


use Apps\Libraries\Log;

use Apps\Vendors\Exception as VendorException;

use Apps\Libraries\ApiClient\Request;
use Apps\Libraries\ApiClient\ResponseInterface;

use Phalconry\Aggregates\Delivery\Order as OrderModel;
use Phalconry\Aggregates\Delivery\Store as StoreModel;

use Apps\Modules\Delivery\Foodtech\Config;

use Apps\Modules\Delivery\Foodtech\OutBounds\Responses\CancelOrderByOrderNo as CancelOrderByOrderNoResponse;

class CancelOrderByOrderNo extends Request
{
    const URL = Config::URL_CANCELORDERBYORDERNO;
    const TOKEN = Config::TOKEN;

    /**
     * @var string $body
     */
    private $body;

    /**
     * @inheritDoc
     */
    protected function createResponse(string $responseText): ResponseInterface
    {
        return new CancelOrderByOrderNoResponse($responseText);
    }

    /**
     * 생성 시 호출되는 초기화 함수. 생성자에 전달되는 $data를 기초로 Request 시에 전송할 body 데이터를 만드는 기능으로 의도되었다.
     * @return array
     * @throws VendorException
     */
    protected function initialize()
    {
        // 데이타 구성
        /**
         * @var array $data
         * @var OrderModel $orderModel
         * @var StoreModel $storeModel
         */
        $data = $this->getData();
        $orderModel = $data['orderModel'];
        $storeModel = $data['storeModel'];
        $externalOrderFoodtechProxy = $data['externalOrderFoodtechProxy'];
        if (is_null($storeModel->getExternalStore())) {
            throw new VendorException(VendorException::ERROR_CODE[913], 913);// exception 처리 - 해당 매장을 알수 없음.
        }

        $storeReception = $orderModel->getStoreReception();

        // ExternalOrderFoodtech 테이블에 존재하면 해당 값을 존재하지 않으면 StoreReception 값을 참조.
        if ( is_null($externalOrderFoodtechProxy)===false) {
            $unidomOrderNo = $externalOrderFoodtechProxy->getUnidomOrderNo();
        } else {
            $unidomOrderNo = $orderModel->getStoreReception()->getReceptionNo();
        }

        $data = [
            'enterpriseCode'    => trim($storeModel->getExternalStore()->getInfo1()),  //기업코드
            'corporationCode'   => trim($storeModel->getExternalStore()->getInfo2()),  //법인코드
            'storeCode'         => trim($storeModel->getExternalStore()->getInfo3()),  //매장코드
            'orderChannel'      => 'WMPO',  //주문채널코드
            'orderNo'           => $orderModel->getOrderDelivery()->getID(),  //채널주문번호(Size range:11)
            'unidomOrderNo'     => (int) $unidomOrderNo,
            'cancelCode'        => 'customer',
        ];

        $this->body = json_encode($data, JSON_UNESCAPED_UNICODE);
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