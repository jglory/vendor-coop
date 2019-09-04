<?php
namespace Phalconry\Aggregates\Delivery;


use Apps\Libraries\Log;

use Phalconry\Aggregates\Aggregate;
use Apps\Libraries\EntityProxy;

use Apps\Vendors\Deliveries\Models\ExternalProduct as ExternalProductModel;
use Apps\Vendors\Deliveries\Models\Order as OrderModel;

use Phalcon\Mvc\Model\Resultset\Simple As SimpleResultset;

use Phalconry\Entities\ExternalProduct As ExternalProductEntity;
use Phalconry\Entities\OrderDelivery As OrderDeliveryEntity;
use Phalconry\Entities\OrderMaster As OrderMasterEntity;
use Phalconry\Entities\Orders As OrdersEntity;
use Phalconry\Entities\Payment As PaymentEntity;
use Phalconry\Entities\PaymentDetail As PaymentDetailEntity;
use Phalconry\Entities\StoreReception As StoreReceptionEntity;
use Phalconry\Entities\WalletTicket As WalletTicketEntity;

class Order extends Aggregate
{
    /**
     * @var EntityProxy $orderMaster
     */
    public $orderMaster;

    /**
     * @var EntityProxy $orderDelivery
     */
    public $orderDelivery;

    /**
     * @var EntityProxy $storeReception
     */
    public $storeReception;

    /**
     * @var EntityProxy[] $storeReceptionHistories
     */
    public $storeReceptionHistories;

    /**
     * @var int $storeID
     */
    public $storeID;

    /**
     * @var int $memberID
     */
    public $memberID;

    /**
     * @var EntityProxy[] $orders
     */
    public $orders;

    /**
     * @var int[] $externalProductIDs
     */
    public $externalProductIDs;

    /**
     * @var EntityProxy|null $payment
     */
    public $payment;

    /**
     * @var EntityProxy[] $paymentDetails
     */
    public $paymentDetails;

    /**
     * Order constructor.
     *
     * @param EntityProxy $orderMaster
     * @param EntityProxy $orderDelivery
     * @param EntityProxy $storeReception
     * @param EntityProxy[] $storeReceptionHistories
     * @param int $storeID
     * @param int $memberID
     * @param EntityProxy[] $orders
     * @param int[] $externalProductIDs
     * @param EntityProxy|null $payment
     * @param EntityProxy[] $paymentDetails
     */
    public function __construct(EntityProxy $orderMaster = null,
                                EntityProxy $orderDelivery = null,
                                EntityProxy $storeReception = null,
                                array $storeReceptionHistories = [],
                                int $storeID = null,
                                int $memberID = null,
                                array $orders = [],
                                array $externalProductIDs = [],
                                EntityProxy $payment = null,
                                array $paymentDetails = [])
    {
        $this->orderMaster = $orderMaster;
        $this->orderDelivery = $orderDelivery;
        $this->storeReception = $storeReception;
        $this->storeReceptionHistories = $storeReceptionHistories;
        $this->storeID = $storeID;
        $this->memberID = $memberID;
        $this->orders = $orders;
        $this->externalProductIDs = $externalProductIDs;
        $this->payment = $payment;
        $this->paymentDetails = $paymentDetails;
    }

    /**
     * @return EntityProxy
     */
    public function getOrderMaster()
    {
        return $this->orderMaster;
    }

    /**
     * @param EntityProxy|null $orderMaster
     * @return self
     */
    public function setOrderMaster($orderMaster): self
    {
        $this->orderMaster = $orderMaster;
        return $this;
    }

    /**
     * @return EntityProxy
     */
    public function getOrderDelivery()
    {
        return $this->orderDelivery;
    }

    /**
     * @param EntityProxy|null $orderDelivery
     * @return self
     */
    public function setOrderDelivery($orderDelivery): self
    {
        $this->orderDelivery = $orderDelivery;
        return $this;
    }

    /**
     * @return EntityProxy|null
     */
    public function getStoreReception()
    {
        return $this->storeReception;
    }

    /**
     * @param EntityProxy|null $storeReception
     * @return self
     */
    public function setStoreReception($storeReception): self
    {
        $this->storeReception = $storeReception;
        return $this;
    }

    /**
     * @return EntityProxy[]|null
     */
    public function getStoreReceptionHistories()
    {
        return $this->storeReceptionHistories;
    }

    /**
     * @return &EntityProxy[]|null
     */
    public function &getStoreReceptionHistoriesRef()
    {
        return $this->storeReceptionHistories;
    }

    /**
     * @param EntityProxy[]|null $storeReceptionHistories
     * @return self
     */
    public function setStoreReceptionHistories($storeReceptionHistories): self
    {
        $this->storeReceptionHistories = $storeReceptionHistories;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getStoreID()
    {
        return $this->storeID;
    }

    /**
     * @param int|null $storeID
     * @return self
     */
    public function setStoreID($storeID): self
    {
        $this->storeID = $storeID;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMemberID()
    {
        return $this->memberID;
    }

    /**
     * @param int|null $memberID
     * @return self
     */
    public function setMemberID($memberID): self
    {
        $this->memberID = $memberID;
        return $this;
    }

    /**
     *
     * @return EntityProxy[]
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     *
     * @return &EntityProxy[]
     */
    public function &getOrdersRef()
    {
        return $this->orders;
    }

    /**
     *
     * @param EntityProxy[]
     * @return self
     */
    public function setOrders($orders): self
    {
        $this->orders = $orders;
        return $this;
    }

    /**
     * @return int[]
     */
    public function getExternalProductIDs()
    {
        return $this->externalProductIDs;
    }

    /**
     * @return &int[]
     */
    public function &getExternalProductIDsRef()
    {
        return $this->externalProductIDs;
    }

    /**
     * @param int[] $externalProductIDSet
     * @return self
     */
    public function setExternalProductIDs($externalProductIDs): self
    {
        $this->externalProductIDs = $externalProductIDs;
        return $this;
    }

    /**
     *
     * @return EntityProxy
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     *
     * @param PaymentEntity|null $payment   - IDE 자동완성을 위한 Annotation. 실타입은 EntityProxy
     * @return self
     */
    public function setPayment($payment): self
    {
        $this->payment = $payment;
        return $this;
    }

    /**
     *
     * @return EntityProxy[]
     */
    public function getPaymentDetails()
    {
        return $this->paymentDetails;
    }

    /**
     *
     * @return &EntityProxy[]
     */
    public function &getPaymentDetailsRef()
    {
        return $this->paymentDetails;
    }

    /**
     *
     * @param EntityProxy[] $paymentDetails
     * @return self
     */
    public function setPaymentDetailSet($paymentDetails): self
    {
        $this->paymentDetails = $paymentDetails;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            'orderMaster' => $this->orderMaster,
            'orderDelivery' => $this->orderDelivery,
            'storeReception' => $this->storeReception,
            'storeReceptionHistories' => $this->storeReceptionHistories,
            'storeID' => $this->storeID,
            'memberID' => $this->memberID,
            'orders' => $this->orders,
            'externalProductIDs' => $this->externalProductIDs,
            'payment' => $this->payment,
            'paymentDetails' => $this->paymentDetails,
        ];
    }
}