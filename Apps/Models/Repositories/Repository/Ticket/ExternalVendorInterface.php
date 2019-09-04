<?php
namespace Phalconry\Repositories\Repository\Ticket;


use Phalconry\Aggregates\Ticket\ExternalVendor as ExternalVendorModel;

interface ExternalVendorInterface
{
    /**
     * ExternalVendor.ID로 ExternalVendor 모델을 검색하여 돌려준다.
     * @param int $externalvendorID
     * @return ExternalVendorModel|bool
     */
    public function findFirstByID(int $externalvendorID);

    /**
     * 주문번호로 ExternalVendor 모델을 검색하여 돌려준다.
     * @param int $orderNo
     * @return ExternalVendorModel|bool
     */
    public function findFirstByOrderNo(int $orderNo);

    /**
     * 매장 아이디로 ExternalVendor 모델을 검색하여 돌려준다.
     * @param int $storeID
     * @return ExternalVendorModel|bool
     */
    public function findFirstByStoreID(int $storeID);
}