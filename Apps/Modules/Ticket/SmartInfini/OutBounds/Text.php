<?php
namespace Apps\Modules\Ticket\SmartInfini\OutBounds;


use Apps\Libraries\Log;

use Apps\Modules\Exception as VendorException;

use Apps\Vendors\Tickets\Models\Member As MemberModel;
use Apps\Vendors\Tickets\Models\Order As OrderModel;

use Apps\Modules\Ticket\SmartInfini\OutBound;

use Apps\Modules\Ticket\SmartInfini\OutBounds\Requests\Text as TextRequest;
use Apps\Modules\Ticket\SmartInfini\OutBounds\Responses\Text as TextResponse;

class Text extends OutBound
{
    /**
     * 상점 아이디
     * @var int $storeID
     */
    private $storeID;
    /**
     * 주문 번호
     * @var int $orderNo
     */
    private $orderNo;
    /**
     * 티켓 코드
     * @var string $ticketCode
     */
    private $ticketCode;

    /**
     * @inheritDoc
     */
    protected function sendRequestToVendor($data)
    {
        return (new TextRequest($data))->request();
    }

    /**
     * CancelOrderByOrderNo constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->storeID = $this->dispatcher->getParam('storeID');
        $this->orderNo = $this->dispatcher->getParam('orderNo');
        $this->ticketCode = $this->dispatcher->getParam('ticketCode');
    }

    /**
     * 벤더사로 보내는 문자재발송 요청을 처리한다.
     */
    public function process()
    {
        $orderModel = $this->vendorService->getOrderModelByVendorCodeStoreIDOrderNo($this->getVendorCode(), $this->storeID, $this->orderNo);
        $memberModel = $this->vendorService->getMemberModelByMemberID($orderModel->getMemberID());
        $found = $orderModel->findWalletTicketByCode($this->ticketCode);
        if ($found === false) {
            throw new VendorException(VendorException::ERROR_CODE[950], 950);
        }

        /** @var TextResponse $response */
        $response = $this->sendRequestToVendor(['orderModel' => $orderModel, 'memberModel' => $memberModel]);
        $responseData = $response->getResponseData();

        $this->fireProcessedEvent([
            'orderModel' => $orderModel,
            'memberModel' => $memberModel,
            'walletTicketProxy' => $found,
        ]);
    }
}