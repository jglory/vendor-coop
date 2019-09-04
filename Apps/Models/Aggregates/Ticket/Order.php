<?php
namespace Phalconry\Aggregates\Ticket;


use Apps\Libraries\Log;

use Apps\Libraries\EntityProxy;
use Phalconry\Aggregates\Aggregate;

use Apps\Vendors\Tickets\Models\ExternalProduct as ExternalProductModel;

use Phalconry\Entities\OrderDeal as OrderDealEntity;
use Phalconry\Entities\OrderMaster as OrderMasterEntity;
use Phalconry\Entities\Payment as PaymentEntity;
use Phalconry\Entities\WalletTicket as WalletTicketEntity;

class Order extends Aggregate
{
    /**
     * @var EntityProxy $orderMaster
     */
    public $orderMaster;

    /**
     * @var EntityProxy $orderDeal
     */
    public $orderDeal;

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
     * @var EntityProxy $externalDeal
     */
    public $externalDeal;

    /**
     * @var int[] $externalProductIDs
     */
    public $externalProductIDs;

    /**
     * @var EntityProxy[] $walletTickets
     */
    public $walletTickets;

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
     * @param EntityProxy $orderDeal
     * @param EntityProxy $storeReception
     * @param int $storeID
     * @param int $memberID
     * @param EntityProxy[] $orders
     * @param EntityProxy $externalDeal;
     * @param int[] $externalProductIDs
     * @param EntityProxy[] $walletTickets
     * @param EntityProxy|null $payment
     * @param EntityProxy[] $paymentDetails
     */
    public function __construct(EntityProxy $orderMaster = null,
                                EntityProxy $orderDeal = null,
                                int $storeID = null,
                                int $memberID = null,
                                array $orders = [],
                                EntityProxy $externalDeal = null,
                                array $externalProductIDs = [],
                                array $walletTickets = [],
                                EntityProxy $payment = null,
                                array $paymentDetails = [])
    {
        $this->orderMaster = $orderMaster;
        $this->orderDeal = $orderDeal;
        $this->storeID = $storeID;
        $this->memberID = $memberID;
        $this->orders = $orders;
        $this->externalDeal = $externalDeal;
        $this->externalProductIDs = $externalProductIDs;
        $this->walletTickets = $walletTickets;
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
    public function getOrderDeal()
    {
        return $this->orderDeal;
    }

    /**
     * @param EntityProxy|null $orderDeal
     * @return self
     */
    public function setOrderDeal($orderDeal): self
    {
        $this->orderDeal = $orderDeal;
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
     * @return EntityProxy
     */
    public function getExternalDeal()
    {
        return $this->externalDeal;
    }

    /**
     * @param EntityProxy $externalDeal
     * @return self
     */
    public function setExternalDeal($externalDeal)
    {
        $this->externalDeal = $externalDeal;
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
     * @return EntityProxy[]
     */
    public function getWalletTickets()
    {
        return $this->walletTickets;
    }

    /**
     *
     * @return &EntityProxy[]
     */
    public function &getWalletTicketsRef()
    {
        return $this->walletTickets;
    }

    /**
     *
     * @param EntityProxy[] $walletTickets
     * @return self
     */
    public function setWalletTickets($walletTickets): self
    {
        $this->walletTickets = $walletTickets;
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
     * @param EntityProxy|null $payment
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
     *
     * @param $ticketCode
     * @return WalletTicketEntity|bool
     */
    public function findWalletTicketByCode($ticketCode)
    {
        /** @var WalletTicketEntity $entityProxy */
        foreach ($this->walletTickets as $entityProxy) {
            if ($entityProxy->getCode()==$ticketCode) {
                return $entityProxy;
            }
        }
        return false;
    }

    /**
     * @param string $menuCode
     * @param ExternalProductModel[] $externalProductModels
     * @return EntityProxy[]
     */
    public function findWalletTicketsByExternalProductMenuCode(string $menuCode, array $externalProductModels)
    {
        /** @var ExternalProductModel[] $foundModels */
        $foundModels = [];
        foreach ($externalProductModels as $model) {
            if ($menuCode === (string)$model->getExternalProduct()->getMenuCode()) {
                $foundModels[] = $model;
            }
        }

        $foundProxies = [];
        foreach ($this->getWalletTickets() as $walletTicketProxy) {
            foreach ($foundModels as $externalProductModel) {
                $externalProductProxy = $externalProductModel->getExternalProduct();
                if ($externalProductProxy->getProductID() === $walletTicketProxy->getProductID()
                    && $externalProductProxy->getProductOptionID() == $walletTicketProxy->getProductOptionID()
                ) {
                    $foundProxies[] = $walletTicketProxy;
                }
            }
        }

        return $foundProxies;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            'orderMaster' => $this->orderMaster,
            'orderDeal' => $this->orderDeal,
            'storeID' => $this->storeID,
            'memberID' => $this->memberID,
            'orders' => $this->orders,
            'externalDeal' => $this->externalDeal,
            'externalProductIDs' => $this->externalProductIDs,
            'walletTickets' => $this->walletTickets,
            'payment' => $this->payment,
            'paymentDetails' => $this->paymentDetails,
        ];
    }
}