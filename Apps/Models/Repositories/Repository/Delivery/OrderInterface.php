<?php
namespace Phalconry\Repositories\Repository\Delivery;


use Phalconry\Aggregates\Delivery\Order as OrderModel;

interface OrderInterface
{
    /**
     * @param int $orderNo
     * @return OrderModel|bool
     */
    public function findFirstOrderByOrderNo(int $orderNo);

    /**
     * @param array $orderNos
     * @param int $page
     * @param int $limit
     * @return \stdClass
     */
    public function findOrdersByOrderNosWithPaging(array $orderNos, int $page, int $limit): \stdClass;

    /**
     * @param int $storeID
     * @param int $beginDt
     * @param int $endDt
     * @param int $page
     * @param int $limit
     * @return \stdClass
     */
    public function findOrdersByStoreIDPeriodWithPaging(int $storeID, int $beginDt, int $endDt, int $page, int $limit): \stdClass;
}