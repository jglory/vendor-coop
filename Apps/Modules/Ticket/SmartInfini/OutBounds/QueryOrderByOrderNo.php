<?php
namespace Apps\Modules\Ticket\SmartInfini\OutBounds;


use Apps\Libraries\Log;

use Apps\Modules\Ticket\SmartInfini\OutBound;

use Apps\Modules\Ticket\SmartInfini\OutBounds\Requests\QueryOrderByOrderNo as QueryOrderByOrderNoRequest;
use Apps\Modules\Ticket\SmartInfini\OutBounds\Responses\QueryOrderByOrderNo as QueryOrderByOrderNoResponse;

class QueryOrderByOrderNo extends OutBound
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
     * @inheritDoc
     */
    protected function sendRequestToVendor($data)
    {
        return (new QueryOrderByOrderNoRequest($data))->request();
    }

    /**
     * CancelOrderByOrderNo constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->storeID = $this->dispatcher->getParam('storeID');
        $this->orderNo = $this->dispatcher->getParam('orderNo');
    }

    /**
     * 벤더사로 보내는 주문 조회 요청을 처리한다.
     */
    public function process()
    {
        $orderModel = $this->vendorService->getOrderModelByVendorCodeStoreIDOrderNo($this->getVendorCode(), $this->storeID, $this->orderNo);

        /** @var QueryOrderByOrderNoResponse $response */
        $response = $this->sendRequestToVendor(['orderModel' => $orderModel]);
        $responseData = $response->getResponseData();

        $this->fireProcessedEvent([
            'orderModel' => $orderModel
        ]);

        return $responseData;
    }
}