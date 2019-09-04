<?php
namespace Apps\Modules\Ticket\SmartInfini\InBounds;


use Apps\Libraries\Log;

use Apps\Common\Constants\ExternalCode;
use Apps\Modules\Ticket\SmartInfini\InBound;
use Phalconry\Entities\Store as EntityStore;

use Phalconry\Aggregates\Ticket\Member As MemberModel;
use Phalconry\Aggregates\Ticket\Order As OrderModel;
use Phalconry\Aggregates\Ticket\Store As StoreModel;

class QueryOrderList extends InBound
{
    /**
     * @var int $storeID
     */
    private $storeID;
    /**
     * @var
     */
    private $orders;
    /**
     * @var
     */
    private $beginDate;
    /**
     * @var
     */
    private $endDate;
    /**
     * @var
     */
    private $page;
    /**
     * @var
     */
    private $limit;

    /**
     * QueryOrderList constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->storeID = $this->request->get('storeID', 'int');
        $this->orderNos = $this->request->get('orderNos');
        $this->beginDate = $this->request->get('beginDate', 'int');
        $this->endDate = $this->request->get('endDate', 'int');
        $this->page = $this->request->get('page', 'int');
        $this->limit = $this->request->get('limit', 'int');
    }

    /**
     * @return mixed|void
     * @throws \Exception
     */
    public function process()
    {
        if (is_null($this->orderNos)) {
            $result = $this->vendorService->getOrderModelsByStoreIDPeriodWithPaging($this->storeID, $this->beginDate, $this->endDate, $this->page, $this->limit);
        } else {
            $result = $this->vendorService->getOrderModelsByOrderNosWithPaging(explode(',', $this->orderNos), $this->page, $this->limit);
        }

        $array = [];
        foreach ($result->items as $orderModel) {
            $memberModel = $this->vendorService->getMemberModelByMemberID($orderModel->getMemberID());
            $storeModel = $this->vendorService->getStoreModelByStoreID($orderModel->getStoreID());
            $array[] = (object)[
                'orderModel' => $orderModel,
                'storeModel' => ($storeModel===false ? null : $storeModel),
                'memberModel' => ($memberModel===false ? null : $memberModel),
            ];
        }
        $result->items = $array;

        return $result;
    }
}