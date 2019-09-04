<?php
namespace Apps\Libraries\ApiClient;


use Apps\Libraries\Curl;
use Apps\Libraries\Log;

/**
 * Class Request
 */
abstract class Request implements RequestInterface
{
    /**
     * 객체 생성 시 전달받은 데이터
     * @var mixed
     */
    private $data;

    /**
     * 대상 서버로 http 요청을 통해 받은 결과값 문자열을 구체적인 응답 객체로 변환하여 돌려준다.
     * @return ResponseInterface
     */
    abstract protected function createResponse(string $responseText) : ResponseInterface;

    /**
     * 생성 시 전달받은 생성 데이터를 돌려준다.
     * @return mixed
     */
    protected function getData()
    {
        return $this->data;
    }

    /**
     * OutBoundRequest constructor.
     * 생성자 호출 시 실행되는 class 메소드 중 initialize가 있다면 실행한다.
     * initialize() 메소드는 생성자에 전달되는 $data를 기초로 Request 시에 전송할 body 데이터를 만드는 기능으로 의도되었다.
     * @param mixed $data
     */
    public function __construct($data)
    {
        $this->data = $data;
        if (method_exists($this, 'initialize')) {
            $this->{'initialize'}();
        }
    }

    /**
     * @inheritDoc
     */
    abstract public function getBody(): string;

    /**
     * @inheritDoc
     */
    abstract public function getMethod(): string;

    /**
     * @inheritDoc
     */
    abstract public function getOptions(): array;

    /**
     * @inheritDoc
     */
    abstract public function getUrl(): string;

    /**
     * @inheritDoc
     */
    public function request(): ResponseInterface
    {
        $method = $this->getMethod();
        $response = $this->createResponse(Curl::$method($this->getUrl(), $this->getBody(), $this->getOptions()));
        Log::debug(get_class($this) . PHP_EOL
            . strtoupper($method) . ' ' . $this->getUrl() . PHP_EOL
            . $this->getBody() . PHP_EOL . PHP_EOL
            . $response->getResponseText());
        return $response;
    }
}