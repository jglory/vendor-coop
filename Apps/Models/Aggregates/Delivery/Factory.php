<?php
namespace Phalconry\Aggregates\Delivery;


use Apps\Libraries\EntityProxy;

use Phalconry\Aggregates\Delivery\ExternalProduct as ExternalProductModel;
use Phalconry\Aggregates\Delivery\ExternalVendor as ExternalVendorModel;
use Phalconry\Aggregates\Delivery\Member as MemberModel;
use Phalconry\Aggregates\Delivery\Order as OrderModel;
use Phalconry\Aggregates\Delivery\Store as StoreModel;

use Phalcon\Mvc\Model\Resultset\Simple as SimpleResultset;

use Phalconry\Entities\ExternalProduct as ExternalProductEntity;
use Phalconry\Entities\ExternalStore as ExternalStoreEntity;
use Phalconry\Entities\ExternalVendor as ExternalVendorEntity;
use Phalconry\Entities\Member as MemberEntity;
use Phalconry\Entities\MemberToken as MemberTokenEntity;
use Phalconry\Entities\Token as TokenEntity;
use Phalconry\Entities\OrderDelivery as OrderDeliveryEntity;
use Phalconry\Entities\OrderMaster as OrderMasterEntity;
use Phalconry\Entities\Payment as PaymentEntity;
use Phalconry\Entities\Store as StoreEntity;
use Phalconry\Entities\StoreReception as StoreReceptionEntity;

class Factory implements FactoryInterface
{
    /**
     * SimpleResultset 를 EntityProxy[] 로 변환한다.
     * @param SimpleResultset $resultset
     * @return EntityProxy[]
     */
    private function simpleResultsetToArray(SimpleResultset $resultset)
    {
        $array = [];
        foreach ($resultset as $entity) {
            $array[] = new EntityProxy($entity);
        }
        return $array;
    }

    /**
     * @inheritDoc
     */
    public function createExternalProduct(ExternalProductEntity $externalProductEntity = null)
    {
        return new ExternalProductModel(new EntityProxy($externalProductEntity));
    }

    /**
     * @inheritDoc
     */
    public function createExternalVendor(ExternalVendorEntity $externalVendorEntity = null)
    {
        return new ExternalVendorModel(new EntityProxy($externalVendorEntity));
    }

    /**
     * @inheritDoc
     */
    public function createMember(MemberEntity $memberEntity = null, MemberTokenEntity $memberTokenEntity = null, TokenEntity $tokenEntity = null) {
        return new MemberModel(new EntityProxy($memberEntity), new EntityProxy($memberTokenEntity), new EntityProxy($tokenEntity));
    }

    /**
     * @inheritDoc
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
                                SimpleResultset $paymentDetailSet = null) {

        $orderMasterProxy = (is_null($orderMasterEntity) ? null : new EntityProxy($orderMasterEntity));
        $orderDeliveryProxy = (is_null($orderDeliveryEntity) ? null : new EntityProxy($orderDeliveryEntity));
        $storeReceptionProxy = (is_null($storeReceptionEntity) ? null : new EntityProxy($storeReceptionEntity));

        $storeReceptionHistoryProxies = (is_null($storeReceptionHistorySet) ? [] : $this->simpleResultsetToArray($storeReceptionHistorySet));

        $orderProxies = (is_null($orderSet) ? [] : $this->simpleResultsetToArray($orderSet));

        $externalProductIDs = [];
        if (is_null($externalProductSet)===false) {
            foreach ($externalProductSet as $entity) {
                $externalProductIDs[] = $entity->getID();
            }
        }

        $paymentProxy = (is_null($paymentEntity) ? null : new EntityProxy($paymentEntity));

        $paymentDetailProxies = (is_null($paymentDetailSet) ? [] : $this->simpleResultsetToArray($paymentDetailSet));

        $orderModel = new OrderModel(
            $orderMasterProxy,
            $orderDeliveryProxy,
            $storeReceptionProxy,
            $storeReceptionHistoryProxies,
            $storeID,
            $memberID,
            $orderProxies,
            $externalProductIDs,
            $paymentProxy,
            $paymentDetailProxies
        );

        return $orderModel;
    }

    /**
     * @inheritDoc
     */
    public function createStore(StoreEntity $store = null, ExternalStoreEntity $externalStore = null)
    {
        $storeProxy = new EntityProxy($store);
        $externalStoreProxy = new EntityProxy($externalStore);
        return new StoreModel($storeProxy, $externalStoreProxy);
    }
}