<?php
namespace Apps\Modules\Ticket\SmartInfini\OutBounds\Responses;


use Apps\Libraries\Log;

use Apps\Libraries\ApiClient\Response;

use Apps\Modules\Ticket\SmartInfini\Config;

class QueryOrderByOrderNo extends Response
{
    const RESPONSE_SUCCESS_CODE = Config::RESPONSE_SUCCESS_CODE;
    const RESPONSE_ERROR_MESSAGES = Config::RESPONSE_ERROR_MESSAGES;

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
        $data = json_decode($this->getResponseText(), true);
        if (is_null($data)) {
            throw new \Exception('연동오류 - json_decode가 실패했습니다. '.json_last_error_msg(), json_last_error());
        }
        if (isset($data['error'])) {
            throw new \Exception('연동오류 - ' . $data['error'] . '. ' . $data['error_description'], 0);
        }
        if (isset($data['code']) === false) {
            throw new \Exception('연동오류 - SmartInfini 로부터의 응답문이 통신 규약에 맞지 않습니다.', 0);
        }
        if ($data['code'] !== self::RESPONSE_SUCCESS_CODE) {
            $message = '연동오류 - ' . $data['code'] . '. ' . self::RESPONSE_ERROR_MESSAGES[$data['code']];
            throw new \Exception($message, 0);
        }
        $this->responseData = $data;
    }

    /**
     * 요청의 응답으로 받은 데이터 중 결과 코드값을 돌려준다.
     * @return string
     */
    public function getResponseCode(): string
    {
        return $this->responseData['code'];
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