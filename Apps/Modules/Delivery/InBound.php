<?php
namespace Apps\Modules\Delivery;


use Apps\Modules\Vendor As VendorBase;
use Phalconry\Services\Service\Delivery\VendorInterface as VendorDeliveryServiceInterface;

abstract class InBound extends VendorBase
{
    /**
     * @var VendorDeliveryServiceInterface $vendorService
     */
    public $vendorService;

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct();
        $this->vendorService = \Phalcon\Di::getDefault()->get('vendorDeliveryService');
    }
}