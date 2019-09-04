<?php
namespace Apps\Libraries\ApiClient;


interface ResponseInterface
{
    /**
     * 평문 형태의 응답 데이터를 돌려준다.
     * @return string
     */
    public function getResponseText(): string;
}