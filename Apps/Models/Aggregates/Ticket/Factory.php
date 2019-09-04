<?php
namespace Phalconry\Aggregates\Ticket;


use Apps\Libraries\EntityProxy;

use Phalconry\Aggregates\Ticket\ExternalProduct As ExternalProductModel;
use Phalconry\Aggregates\Ticket\ExternalVendor As ExternalVendorModel;
use Phalconry\Aggregates\Ticket\Member As MemberModel;
use Phalconry\Aggregates\Ticket\Order As OrderModel;
use Phalconry\Aggregates\Ticket\Store As StoreModel;

use Phalcon\Mvc\Model\Resultset\Simple As SimpleResultset;

use Phalconry\Entities\ExternalDeal As ExternalDealEntity;
use Phalconry\Entities\ExternalProduct As ExternalProductEntity;
use Phalconry\Entities\ExternalVendor As ExternalVendorEntity;
use Phalconry\Entities\Member As MemberEntity;
use Phalconry\Entities\MemberToken As MemberTokenEntity;
use Phalconry\Entities\Token As TokenEntity;
use Phalconry\Entities\OrderDeal As OrderDealEntity;
use Phalconry\Entities\OrderMaster As OrderMasterEntity;
use Phalconry\Entities\Payment As PaymentEntity;
use Phalconry\Entities\Store As StoreEntity;
use Phalconry\Entities\StoreReception As StoreReceptionEntity;

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
                                OrderDealEntity $orderDealEntity = null,
                                int $storeID = null,
                                int $memberID = null,
                                SimpleResultset $orderSet = null,
                                ExternalDealEntity $externalDealEntity = null,
                                SimpleResultset $externalProductSet = null,
                                SimpleResultset $walletTicketSet = null,
                                PaymentEntity $paymentEntity = null,
                                SimpleResultset $paymentDetailSet = null) {

        $orderMasterProxy = (is_null($orderMasterEntity) ? null : new EntityProxy($orderMasterEntity));
        $orderDealProxy = (is_null($orderDealEntity) ? null : new EntityProxy($orderDealEntity));

        $orderProxies = (is_null($orderSet) ? [] : $this->simpleResultsetToArray($orderSet));

        $externalDealProxy = (is_null($externalDealEntity) ? null : new EntityProxy($externalDealEntity));
        $externalProductIDs = [];
        if (is_null($externalProductSet)===false) {
            foreach ($externalProductSet as $entity) {
                $externalProductIDs[] = $entity->getID();
            }
        }

        $walletTicketProxies = (is_null($walletTicketSet) ? [] : $this->simpleResultsetToArray($walletTicketSet));

        $paymentProxy = (is_null($paymentEntity) ? null : new EntityProxy($paymentEntity));

        $paymentDetailProxies = (is_null($paymentDetailSet) ? [] : $this->simpleResultsetToArray($paymentDetailSet));

        $orderModel = new OrderModel(
            $orderMasterProxy,
            $orderDealProxy,
            $storeID,
            $memberID,
            $orderProxies,
            $externalDealProxy,
            $externalProductIDs,
            $walletTicketProxies,
            $paymentProxy,
            $paymentDetailProxies
        );

        return $orderModel;
    }

    /**
     * @inheritDoc
     */
    public function createStore(StoreEntity $store = null, int $storeBranchID = null) {
        $storeProxy = new EntityProxy($store);
        return new StoreModel($storeProxy, $storeBranchID);
    }
}