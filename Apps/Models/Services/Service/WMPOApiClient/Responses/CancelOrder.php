<?php
namespace Phalconry\Services\Service\WMPOApiClient\Responses;


use Apps\Libraries\Log;

use Apps\Libraries\ApiClient\Response;

class CancelOrder extends Response
{
    const RESPONSE_SUCCESS_CODE = 1;

    /**
     * 응답 데이터. responseText 를 처리 가능한 형태로 변환한 값.
     * @var mixed $responseData
     */
    private $responseData;

    /**
     * @inheritDoc
     * @throws \Exception
     */
    protected function initialize()
    {
        $this->responseData = json_decode($this->getResponseText(), true);
    }

    /**
     * 요청의 응답으로 받은 데이터 중 결과 코드값을 돌려준다.
     * @return int
     */
    public function getResponseCode(): int
    {
        return $this->responseData['result']['code'];
    }

    /**
     * 생성자의 입력값 responseText를 로직 처리 가능한 형태로 변환한 responseData를 돌려 준다.
     * @return array
     */
    public function getResponseData(): array
    {
        return $this->responseData;
    }
}