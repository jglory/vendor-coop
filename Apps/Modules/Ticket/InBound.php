<?php
namespace Apps\Modules\Ticket;


use Apps\Modules\Vendor As VendorBase;
use Phalconry\Services\Service\Ticket\VendorInterface as VendorTicketServiceInterface;

abstract class InBound extends VendorBase
{
    /**
     * @var VendorTicketServiceInterface $vendorService
     */
    public $vendorService;

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct();
        $this->vendorService = \Phalcon\Di::getDefault()->get('vendorTicketService');
    }
}