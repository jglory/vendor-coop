<?php
namespace Apps\Modules\Delivery;


use Apps\Modules\Vendor as VendorBase;
use Phalconry\Services\Service\Delivery\VendorInterface as VendorDeliveryServiceInterface;
use Phalconry\Aggregates\Delivery\FactoryInterface as VendorDeliveryModelFactoryInterface;

abstract class OutBound extends VendorBase
{
    /**
     * @var VendorDeliveryServiceInterface $vendorService
     */
    public $vendorService;

    /**
     * @var VendorDeliveryModelFactoryInterface $vendorModelFactory
     */
    public $vendorModelFactory;

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct();
        $this->vendorService = \Phalcon\Di::getDefault()->get('vendorDeliveryService');
        $this->vendorModelFactory = \Phalcon\Di::getDefault()->get('vendorDeliveryModelFactory');
    }

    /**
     * @param \JsonSerializable|array $data
     * @return mixed
     */
    abstract protected function sendRequestToVendor($data);
}