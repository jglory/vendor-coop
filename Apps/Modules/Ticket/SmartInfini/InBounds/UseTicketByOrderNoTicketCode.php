<?php
namespace Apps\Modules\Ticket\SmartInfini\InBounds;


use Apps\Libraries\Log;

use Apps\Modules\Exception As VendorException;
use Apps\Modules\Ticket\SmartInfini\InBound;

use Phalconry\Entities\WalletTicket As WalletTicketEntity;

use Phalconry\Services\Service\WMPOApiClient\Responses\UseTicket as UseTicketResponse;

class UseTicketByOrderNoTicketCode extends InBound
{
    /**
     * @var string $vendorCode
     */
    private $vendorCode;
    /**
     * @var int $orderNo
     */
    private $orderNo;
    /**
     * @var string $ticketCode
     */
    private $ticketCode;

    /**
     * UseTicketByOrderNoTicketCode constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->vendorCode = $this->dispatcher->getParam('vendorCode');
        $this->orderNo = $this->dispatcher->getParam('orderNo');
        $this->ticketCode = $this->dispatcher->getParam('ticketCode');
    }

    /**
     * @return mixed
     */
    public function process()
    {
        $orderModel = $this->vendorService->getOrderModelByOrderNo($this->orderNo);
        $found = $orderModel->findWalletTicketByCode($this->ticketCode);
        if ($found===false) {
            throw new VendorException(VendorException::ERROR_CODE[950], 950);
        }
        $externalVendorModel = $this->vendorService->getExternalVendorModelByStoreID($orderModel->getStoreID());
        if ($this->vendorCode!==$externalVendorModel->getExternalVendor()->getCode()) {
            throw new VendorException(VendorException::ERROR_CODE[905], 905);
        }
        $storeModel = $this->vendorService->getStoreModelByStoreID($orderModel->getStoreID());

        /** @var UseTicketResponse $response */
        $response = $this->wmpoApiClient->sendUseTicket($orderModel, $this->ticketCode, $storeModel->getStoreBranchID());
        return [
            'result' => $response->getResponseCode()
        ];
    }
}