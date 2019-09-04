<?php
namespace Phalconry\Services\Service;


interface VendorEventListenerInterface
{
    /**
     * 벤더사 처리 완료 이벤트를 처리한다.
     * @param \Phalcon\Events\Event $event
     * @param object $source
     * @param mixed $data
     */
    public function onProcessed($event, $source, $data);
}