<?php
namespace Phalconry\Services\Service\Ticket;

use Phalconry\Repositories\Repository\VendorRepositoryInterface;

use Phalconry\Aggregates\Ticket\ExternalProduct As ExternalProductModel;
use Phalconry\Aggregates\Ticket\ExternalVendor As ExternalVendorModel;
use Phalconry\Aggregates\Ticket\Member As MemberModel;
use Phalconry\Aggregates\Ticket\Order As OrderModel;
use Phalconry\Aggregates\Ticket\Store As StoreModel;

interface VendorInterface extends VendorRepositoryInterface
{
    /**
     * @param int $externalproductID
     * @return ExternalProductModel
     */
    public function getExternalProductModelByExternalProductID(int $externalproductID): ExternalProductModel;

    /**
     * @param int $orderNo
     * @return ExternalProductModel[]
     */
    public function getExternalProductModelSetByOrderNo(int $orderNo): array;

    /**
     * @param int $externalvendorID
     * @return ExternalVendorModel
     */
    public function getExternalVendorModelByExternalVendorID(int $externalvendorID): ExternalVendorModel;

    /**
     * 주문번호로 ExternalVendor 모델을 검색하여 돌려준다.
     * @param int $orderNo
     * @return ExternalVendorModel
     */
    public function getExternalVendorModelByOrderNo(int $orderNo): ExternalVendorModel;

    /**
     * @param int $storeID
     * @return ExternalVendorModel
     */
    public function getExternalVendorModelByStoreID(int $storeID): ExternalVendorModel;

    /**
     * @param int $memberID
     * @return MemberModel
     */
    public function getMemberModelByMemberID(int $memberID): MemberModel;

    /**
     * @param int $orderNo
     * @return OrderModel
     */
    public function getOrderModelByOrderNo(int $orderNo): OrderModel;

    /**
     * @param int[] $orderNos
     * @param int $page
     * @param int $limit
     * @return \stdClass
     */
    public function getOrderModelsByOrderNosWithPaging(array $orderNos, int $page, int $limit): \stdClass;

    /**
     * @param int $storeID
     * @param int $beginDt
     * @param int $endDt
     * @param int $page
     * @param int $limit
     * @return \stdClass
     */
    public function getOrderModelsByStoreIDPeriodWithPaging(int $storeID, int $beginDt, int $endDt, int $page, int $limit): \stdClass;

    /**
     * @param string $vendorCode
     * @param int $storeID
     * @return OrderModel|bool
     */
    public function getOrderModelByVendorCodeStoreIDOrderNo(string $vendorCode, int $storeID, int $orderNo);

    /**
     * @param int $storeID
     * @return StoreModel
     */
    public function getStoreModelByStoreID(int $storeID): StoreModel;
}