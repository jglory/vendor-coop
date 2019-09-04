<?php
namespace Phalconry\Repositories\Repository\Delivery;


use Phalconry\Aggregates\Delivery\ExternalVendor as ExternalVendorModel;

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
     * @return \Apps\Vendors\Tickets\Models\ExternalVendor|bool
     */
    public function findFirstByOrderNo(int $orderNo);
}