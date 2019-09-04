<?php
namespace Apps\Controllers;


use Apps\Libraries\Curl;
use Apps\Libraries\Log;

use Apps\Modules\Exception as VendorException;
use Apps\Modules\VendorInterface;
use Phalconry\Services\Service\VendorModuleResolverInterface;


class InBound2Controller extends \Phalcon\Mvc\Controller
{
    use \Apps\Traits\API;
    use \Apps\Common\Traits\ExceptionLogTrait;

    /**
     * @var VendorInterface $vendor
     */
    private $vendor;
    /**
     * @var string $vendorCode
     */
    private $vendorCode;
    /**
     * @var int $orderNo
     */
    private $orderNo;
    /**
     * @var string $actionName
     */
    private $actionName;
    /**
     * @return VendorModuleResolverInterface
     */
    private $vendorModuleResolver;

    /**
     * onConstruct 이벤트 핸들러 메소드
     */
    public function onConstruct()
    {
        $this->vendorModuleResolver = \Phalcon\Di::getDefault()->get('vendorModuleResolver');
    }

    /**
     * Event : beforeExecuteRoute
     */
    public function beforeExecuteRoute($dispatcher)
    {
        try {
            $this->vendorCode = $dispatcher->getParam('vendorCode');
            $this->actionName = $dispatcher->getActionName();

            $vendor = $this->vendorModuleResolver->resolve($this->vendorCode, VendorModuleResolverInterface::DIRECTION_INBOUND, $this->actionName);
            if (is_null($vendor)) {
                throw new VendorException(VendorException::ERROR_CODE[902], 902);
            }
            $this->vendor = $vendor;
        } catch (\Exception $e) {
            Log::error($this->makeExceptionLogString($e));
            $this->setResult($e->getCode(), $e->getMessage());
            $this->send();
            return false;
        }

        return true;
    }

    /**
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call(string $method, array $args)
    {
        try {
            $this->send($this->vendor->process());
        } catch (\Exception $e) {
            Log::error($this->makeExceptionLogString($e));
            $this->setResult($e->getCode(), $e->getMessage());
            $this->send();
            return;
        }
    }
}