<?php
namespace Apps\Libraries\ApiClient;


use Apps\Libraries\Log;

abstract class Response implements ResponseInterface
{
    /**
     * 평문 형태의 응답 데이터
     * @var string $responseText
     */
    private $responseText;

    /**
     * OutBoundResponse constructor..
     * 생성자 호출 시 실행되는 class 메소드 중 initialize가 있다면 실행한다.
     * initialize() 메소드는 서브 클래스에서 생성자 역할을 하기 위해 정의되었다.
     * @param $responseText
     */
    public function __construct(string $responseText = '')
    {
        $this->responseText = $responseText;
        if (method_exists($this, 'initialize')) {
            $this->{'initialize'}();
        }
    }

    /**
     * @inheritDoc
     */
    public function getResponseText(): string
    {
        return $this->responseText;
    }
}