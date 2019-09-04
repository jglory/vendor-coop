<?php
namespace Phalconry\Services\Service\Delivery;


use Phalconry\Repositories\Repository\VendorRepositoryInterface;

use Phalconry\Aggregates\Delivery\ExternalVendor As ExternalVendorModel;
use Phalconry\Aggregates\Delivery\Member As MemberModel;
use Phalconry\Aggregates\Delivery\Order As OrderModel;
use Phalconry\Aggregates\Delivery\Store As StoreModel;

interface VendorInterface extends VendorRepositoryInterface
{
    /**
     * @param int $externalvendorID
     * @return ExternalVendorModel
     */
    public function getExternalVendorModelByExternalVendorID(int $externalvendorID);

    /**
     * @param int $storeID
     * @return ExternalVendorModel
     */
    public function getExternalVendorModelByStoreID(int $storeID);

    /**
     * 주문번호로 ExternalVendor 모델을 검색하여 돌려준다.
     * @param int $orderNo
     * @return ExternalVendorModel
     */
    public function getExternalVendorModelByOrderNo(int $orderNo): ExternalVendorModel;

    /**
     * @param int $memberID
     * @return MemberModel
     */
    public function getMemberModelByMemberID(int $memberID);

    /**
     * @param int $orderNo
     * @return OrderModel
     */
    public function getOrderModelByOrderNo(int $orderNo);

    /**
     * @param string $vendorCode
     * @param int $storeID
     * @return OrderModel
     */
    public function getOrderModelByVendorCodeStoreIDOrderNo(string $vendorCode, int $storeID, int $orderNo);

    /**
     * @param int $storeID
     * @return StoreModel
     */
    public function getStoreModelByStoreID(int $storeID);
}