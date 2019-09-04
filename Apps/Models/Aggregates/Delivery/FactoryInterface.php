<?php
namespace Phalconry\Aggregates\Delivery;


use Apps\Libraries\EntityProxy;

use Phalconry\Aggregates\Delivery\ExternalProduct as ExternalProductModel;
use Phalconry\Aggregates\Delivery\ExternalVendor as ExternalVendorModel;
use Phalconry\Aggregates\Delivery\Member as MemberModel;
use Phalconry\Aggregates\Delivery\Order as OrderModel;
use Phalconry\Aggregates\Delivery\Store as StoreModel;

use Phalcon\Mvc\Model\Resultset\Simple As SimpleResultset;

use Phalconry\Entities\ExternalProduct as ExternalProductEntity;
use Phalconry\Entities\ExternalStore as ExternalStoreEntity;
use Phalconry\Entities\ExternalVendor as ExternalVendorEntity;
use Phalconry\Entities\Member as MemberEntity;
use Phalconry\Entities\MemberToken as MemberTokenEntity;
use Phalconry\Entities\MemberToken;
use Phalconry\Entities\OrderDelivery as OrderDeliveryEntity;
use Phalconry\Entities\OrderMaster as OrderMasterEntity;
use Phalconry\Entities\Payment as PaymentEntity;
use Phalconry\Entities\Store as StoreEntity;
use Phalconry\Entities\StoreReception as StoreReceptionEntity;
use Phalconry\Entities\StoreReceptionHistory as StoreReceptionHistoryEntity;
use Phalconry\Entities\Token as TokenEntity;

interface FactoryInterface
{
    /**
     * @param ExternalProductEntity $externalProductEntity
     * @return ExternalProductModel
     */
    public function createExternalProduct(ExternalProductEntity $externalProductEntity = null);

    /**
     * @param ExternalVendorEntity $externalVendorEntity
     * @return ExternalVendorModel
     */
    public function createExternalVendor(ExternalVendorEntity $externalVendorEntity = null);

    /**
     * @param MemberEntity $memberEntity
     * @param MemberTokenEntity $memberTokenEntity
     * @param TokenEntity $tokenEntity
     * @return MemberModel
     */
    public function createMember(MemberEntity $memberEntity = null, MemberTokenEntity $memberTokenEntity = null, TokenEntity $tokenEntity = null);

    /**
     * @param OrderMasterEntity|null $orderMasterEntity
     * @param OrderDeliveryEntity|null $orderDeliveryEntity
     * @param StoreReceptionEntity|null $storeReceptionEntity
     * @param SimpleResultset|null $storeReceptionHistorySet,
     * @param int|null $storeID
     * @param int|null $memberID
     * @param SimpleResultset|null $orderSet
     * @param SimpleResultset|null $externalProductSet
     * @param PaymentEntity|null $paymentEntity
     * @param SimpleResultset|null $paymentDetailSet
     * @return OrderModel
     */
    public function createOrder(OrderMasterEntity $orderMasterEntity = null,
                                OrderDeliveryEntity $orderDeliveryEntity = null,
                                StoreReceptionEntity $storeReceptionEntity = null,
                                SimpleResultset $storeReceptionHistorySet = null,
                                int $storeID = null,
                                int $memberID = null,
                                SimpleResultset $orderSet = null,
                                SimpleResultset $externalProductSet = null,
                                PaymentEntity $paymentEntity = null,
                                SimpleResultset $paymentDetailSet = null);
    /**
     * @param StoreEntity $store
     * @param ExternalStoreEntity $externalStore
     * @return StoreModel
     */
    public function createStore(StoreEntity $store = null, ExternalStoreEntity $externalStore = null);
}