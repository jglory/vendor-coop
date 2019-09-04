<?php
namespace Apps\Controllers;

use Apps\Common\Constants\ExternalCode;

use Apps\Libraries\Log;

use Apps\Modules\Exception as VendorException;
use Apps\Modules\VendorInterface;

use Phalconry\Services\Service\VendorModuleResolverInterface;
use Phalconry\Services\Service\Delivery\VendorInterface as VendorDeliveryServiceInterface;
use Phalconry\Services\Service\Ticket\VendorInterface as VendorTicketServiceInterface;

/**
 * flow: core -> external -> vendor
 */
class OutBound2Controller extends \Phalcon\Mvc\Controller
{
    use \Apps\Traits\API;
    use \Apps\Common\Traits\ExceptionLogTrait;

    /**
     * @var VendorInterface $vendor
     */
    private $vendor;
    /**
     * @var int $storeID
     */
    private $storeID;
    /**
     * @var int $orderNo
     */
    private $orderNo;
    /**
     * @var string $actionName
     */
    private $actionName;
    /**
     * @var VendorModuleResolverInterface
     */
    private $vendorModuleResolver;
    /**
     * @var VendorDeliveryServiceInterface
     */
    private $vendorDeliveryService;
    /**
     * @var VendorTicketServiceInterface
     */
    private $vendorTicketService;

    /**
     * onConstruct 이벤트 핸들러 메소드
     */
    public function onConstruct()
    {
        $this->vendorModuleResolver = \Phalcon\Di::getDefault()->get('vendorModuleResolver');
        $this->vendorDeliveryService = \Phalcon\Di::getDefault()->get('vendorDeliveryService');
        $this->vendorTicketService = \Phalcon\Di::getDefault()->get('vendorTicketService');
    }

    /**
     * Event : beforeExecuteRoute
     */
    public function beforeExecuteRoute($dispatcher)
    {
        try {
            $this->storeID = $dispatcher->getParam('storeID');
            $this->orderNo = $dispatcher->getParam('orderNo');
            $this->actionName = $dispatcher->getActionName();

            Log::debug(__METHOD__ . ': ' . json_encode([
                'storeID' => $this->storeID,
                    'orderNo' => $this->orderNo,
                    'actionName' => $this->actionName
                ], JSON_UNESCAPED_UNICODE));

            try {
                $externalVendorModel = $this->vendorDeliveryService->getExternalVendorModelByOrderNo($this->orderNo);
            } catch (VendorException $e) {
                $externalVendorModel = $this->vendorTicketService->getExternalVendorModelByOrderNo($this->orderNo);
            }

            $vendor = $this->vendorModuleResolver->resolve($externalVendorModel->getExternalVendor()->getCode(), VendorModuleResolverInterface::DIRECTION_OUTBOUND, $this->actionName);
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