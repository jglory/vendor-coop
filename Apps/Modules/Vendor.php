<?php
namespace Apps\Modules;


use Apps\Libraries\Log;

use Phalcon\Events\ManagerInterface As EventsManagerInterface;
use Phalcon\Http\RequestInterface;
use Phalcon\Mvc\Model\Transaction\ManagerInterface as TransactionManagerInterface;
use Phalcon\Mvc\DispatcherInterface;

use Phalconry\Services\Service\PushMessageInterface as PushMessageServiceInterface;
use Phalconry\Services\Service\WMPOApiClient;

abstract class Vendor implements VendorInterface
{
    const PROCESSED_EVENT_NAME = 'Vendor:onProcessed';

    /**
     * @var DispatcherInterface $dispatcher
     */
    public $dispatcher;
    /**
     * @var RequestInterface $dispatcher
     */
    public $request;
    /**
     * @var EventsManagerInterface $eventManager
     */
    public $eventsManager;
    /**
     * @var PushMessageServiceInterface $pushMessageService
     */
    public $pushMessageService;

    /**
     * @var TransactionManagerInterface $transactionsManager
     */
    public $transactionsManager;

    /**
     * @var WMPOApiClient $wmpoApiClient
     */
    public $wmpoApiClient;


    /**
     * 처리 완료 이벤트를 발생한다.
     * @param $data
     */
    protected final function fireProcessedEvent($data)
    {
        // 처리 완료 이벤트 발생!
        $this->eventsManager->fire($this->getProcessedEventName(), $this, $data);
    }

    /**
     * 처리 완료 이벤트에 사용되는 이벤트명을 돌려준다.
     * @return string
     */
    protected final function getProcessedEventName()
    {
        return self::PROCESSED_EVENT_NAME;
    }

    /**
     * Vendor constructor.
     */
    public function __construct()
    {
        $this->dispatcher = \Phalcon\Di::getDefault()->get('dispatcher');
        $this->request = \Phalcon\Di::getDefault()->get('request');
        $this->eventsManager = \Phalcon\Di::getDefault()->get('eventsManager');
        $this->pushMessageService = \Phalcon\Di::getDefault()->get('pushMessageService');
        $this->transactionsManager = \Phalcon\Di::getDefault()->get('transactionsManager');
        $this->wmpoApiClient = \Phalcon\Di::getDefault()->get('wmpoApiClient');
    }

    /**
     * Vendor Code를 돌려준다.
     * @return string
     */
    abstract public function getVendorCode() : string;

    /**
     * 처리를 수행한다. 상속 받은 자식 클래스에서 처리의 구체적인 내용을 구현하도록 의도되었다.
     * @return mixed
     * @throws \Exception
     */
    abstract public function process();
}