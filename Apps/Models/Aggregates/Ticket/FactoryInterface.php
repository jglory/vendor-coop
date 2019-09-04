<?php
namespace Phalconry\Aggregates\Ticket;


use Apps\Libraries\EntityProxy;

use Phalconry\Aggregates\Ticket\ExternalProduct as ExternalProductModel;
use Phalconry\Aggregates\Ticket\ExternalVendor as ExternalVendorModel;
use Phalconry\Aggregates\Ticket\Member as MemberModel;
use Phalconry\Aggregates\Ticket\Order as OrderModel;
use Phalconry\Aggregates\Ticket\Store as StoreModel;

use Phalcon\Mvc\Model\Resultset\Simple As SimpleResultset;

use Phalconry\Entities\ExternalDeal as ExternalDealEntity;
use Phalconry\Entities\ExternalProduct as ExternalProductEntity;
use Phalconry\Entities\ExternalVendor as ExternalVendorEntity;
use Phalconry\Entities\Member as MemberEntity;
use Phalconry\Entities\MemberToken as MemberTokenEntity;
use Phalconry\Entities\MemberToken;
use Phalconry\Entities\OrderDeal as OrderDealEntity;
use Phalconry\Entities\OrderMaster as OrderMasterEntity;
use Phalconry\Entities\Payment as PaymentEntity;
use Phalconry\Entities\Store as StoreEntity;
use Phalconry\Entities\StoreReception as StoreReceptionEntity;
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
     * @param OrderDealEntity|null $orderDealEntity
     * @param int|null $storeID
     * @param int|null $memberID
     * @param SimpleResultset|null $orderSet
     * @param ExternalDealEntity $externalDealEntity,
     * @param SimpleResultset|null $externalProductSet
     * @param SimpleResultset|null $walletTicketSet
     * @param PaymentEntity|null $paymentEntity
     * @param SimpleResultset|null $paymentDetailSet
     * @return OrderModel
     */
    public function createOrder(OrderMasterEntity $orderMasterEntity = null,
                                OrderDealEntity $orderDealEntity = null,
                                int $storeID = null,
                                int $memberID = null,
                                SimpleResultset $orderSet = null,
                                ExternalDealEntity $externalDealEntity = null,
                                SimpleResultset $externalProductSet = null,
                                SimpleResultset $walletTicketSet = null,
                                PaymentEntity $paymentEntity = null,
                                SimpleResultset $paymentDetailSet = null);
    /**
     * @param StoreEntity $store
     * @param int $storeBranchID
     * @return StoreModel
     */
    public function createStore(StoreEntity $store = null, int $storeBranchID = null);
}