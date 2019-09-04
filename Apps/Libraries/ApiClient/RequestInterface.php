<?php
namespace Apps\Libraries\ApiClient;


interface RequestInterface
{
    const METHOD_DELETE = 'delete';
    const METHOD_GET = 'get';
    const METHOD_PATCH = 'patch';
    const METHOD_POST = 'post';
    const METHOD_PUT = 'put';

    /**
     * HTTP 요청 형식값을 돌려준다.
     * @return string
     */
    public function getMethod(): string;

    /**
     * URL을 돌려준다.
     * @return string
     */
    public function getUrl(): string;

    /**
     * HTTP 설정 옵션값을 배열로 돌려준다.
     * @return array
     */
    public function getOptions(): array;

    /**
     * HTTP 요청 시 전달될 body 데이터를 돌려준다.
     * @return string
     */
    public function getBody(): string;

    /**
     * HTTP 요청을 실행한다.
     * @return mixed
     */
    public function request();
}