<?php
namespace Apps\Modules\Ticket;


use Apps\Modules\Vendor as VendorBase;
use Phalconry\Services\Service\Ticket\VendorInterface as VendorTicketServiceInterface;

abstract class OutBound extends VendorBase
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

    /**
     * 벤더사로 주문 취소 요청을 보낸다.
     * @param \JsonSerializable|array $data
     * @return mixed
     */
    abstract protected function sendRequestToVendor($data);
}