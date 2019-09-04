<?php
namespace Phalconry\Services\Service\Ticket;


use Apps\Libraries\Log;

use Apps\Modules\Exception as VendorException;

use Phalconry\Aggregates\Aggregate;
use Phalconry\Aggregates\Ticket\ExternalProduct as ExternalProductModel;
use Phalconry\Aggregates\Ticket\ExternalVendor as ExternalVendorModel;
use Phalconry\Aggregates\Ticket\Member as MemberModel;
use Phalconry\Aggregates\Ticket\Order as OrderModel;
use Phalconry\Aggregates\Ticket\Store as StoreModel;

use Phalconry\Repositories\Repository\Ticket\ExternalVendorInterface as ExternalVendorRepositoryInterface;
use Phalconry\Repositories\Repository\Ticket\ExternalProductInterface as ExternalProductRepositoryInterface;
use Phalconry\Repositories\Repository\Ticket\MemberInterface as MemberRepositoryInterface;
use Phalconry\Repositories\Repository\Ticket\OrderInterface as OrderRepositoryInterface;
use Phalconry\Repositories\Repository\Ticket\StoreInterface as StoreRepositoryInterface;

use Phalcon\Mvc\Model\TransactionInterface;

/**
 * Class Vendor
 * @package Apps\Vendors\Tickets\Services
 * @property ExternalProductRepositoryInterface $externalProductRepository
 * @property ExternalVendorRepositoryInterface $externalVendorRepository
 * @property MemberRepositoryInterface $memberRepository
 * @property OrderRepositoryInterface $orderRepository
 * @property StoreRepositoryInterface $storeRepository
 */
class Vendor implements VendorInterface
{
    /**
     * @inheritdoc
     */
    public function getExternalProductModelByExternalProductID(int $externalproductID): ExternalProductModel
    {
        $model = $this->externalProductRepository->findFirstByID($externalproductID);
        if ($model === false) {
            throw new VendorException(VendorException::ERROR_CODE[960], 960);
        }
        return $model;
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function getExternalProductModelSetByOrderNo(int $orderNo): array
    {
        $models = $this->externalProductRepository->findAllByOrderNo($orderNo);
        if (count($models) === 0) {
            throw new VendorException(VendorException::ERROR_CODE[961], 961);
        }
        return $models;
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function getExternalVendorModelByExternalVendorID(int $externalvendorID): ExternalVendorModel
    {
        /** @var ExternalVendorModel $externalvendorModel */
        $model = $this->externalVendorRepository->findFirstByID($externalvendorID);
        if ($model === false) {
            throw new VendorException(VendorException::ERROR_CODE[904], 904);
        }
        return $model;
    }

    /**
     * @inheritDoc
     * $throws VendorException
     */
    public function getExternalVendorModelByOrderNo(int $orderNo): ExternalVendorModel
    {
        $externalVendorModel = $this->externalVendorRepository->findFirstByOrderNo($orderNo);
        if ($externalVendorModel===false) {
            throw new VendorException(VendorException::ERROR_CODE[920], 920);
        }
        return $externalVendorModel;
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function getExternalVendorModelByStoreID(int $storeID): ExternalVendorModel
    {
        /** @var StoreModel $storeModel */
        $storeModel = $this->getStoreModelByStoreID($storeID);
        /** @var ExternalVendorModel $externalvendorModel */
        $externalvendorModel = $this->getExternalVendorModelByExternalVendorID($storeModel->getStore()->getIsLink());

        return $externalvendorModel;
    }

    /**
     * @inheritdoc
     */
    public function getMemberModelByMemberID(int $memberID): MemberModel
    {
        /** @var StoreModel $storeModel */
        $model = $this->memberRepository->findFirstByID($memberID);
        if ($model === false) {
            throw new VendorException(VendorException::ERROR_CODE[970], 970);
        }
        return $model;
    }

    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function getOrderModelByOrderNo(int $orderNo): OrderModel
    {
        /** @var OrderModel $orderModel */
        $model = $this->orderRepository->findFirstOrderByOrderNo($orderNo);
        if ($model === false) {
            throw new VendorException(VendorException::ERROR_CODE[920], 920);
        }
        return $model;
    }

    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function getOrderModelsByOrderNosWithPaging(array $orderNos, int $page = 1, int $limit = 10): \stdClass
    {
        return $this->orderRepository->findOrdersByOrderNosWithPaging($orderNos, $page, $limit);
    }

    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function getOrderModelsByStoreIDPeriodWithPaging(int $storeID, int $beginDt, int $endDt, int $page = 1, int $limit = 10): \stdClass
    {
        return $this->orderRepository->findOrdersByStoreIDPeriodWithPaging($storeID, $beginDt, $endDt, $page, $limit);
    }

    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function getOrderModelByVendorCodeStoreIDOrderNo(string $vendorCode, int $storeID, int $orderNo): OrderModel
    {
        /** @var StoreModel $storeModel */
        $storeModel = $this->getStoreModelByStoreID($storeID);

        /** @var OrderModel $orderModel */
        $orderModel = $this->getOrderModelByOrderNo($orderNo);
        if ($orderModel->getStoreID() != $storeID) {
            throw new VendorException(VendorException::ERROR_CODE[912], 912);
        }

        /** @var ExternalVendorModel $externalvendorModel */
        $externalvendorModel = $this->getExternalVendorModelByExternalVendorID($orderModel->getExternalDeal()->getVendorID());

        if ($vendorCode !== $externalvendorModel->getExternalVendor()->getCode()) {
            throw new VendorException(VendorException::ERROR_CODE[905], 905);
        }

        return $orderModel;
    }

    /**
     * {@inheritdoc}
     * @throws VendorException
     */
    public function getStoreModelByStoreID(int $storeID): StoreModel
    {
        /** @var StoreModel $storeModel */
        $storeModel = $this->storeRepository->findFirstByID($storeID);
        if ($storeModel === false) {
            throw new VendorException(VendorException::ERROR_CODE[910], 910);
        }
        return $storeModel;
    }

    /**
     * CUD(Create/Update/Delete) 를 처리한다.
     * @param string $method
     * @param $model
     * @param $transaction
     */
    private function processCUD(string $method, $model, $transaction)
    {
        if ($model instanceof \Phalconry\Aggregates\Ticket\ExternalProduct) {
            $this->externalProductRepository->$method($model, $transaction);
        } elseif ($model instanceof \Phalconry\Aggregates\Ticket\ExternalVendor) {
            $this->externalVendorRepository->$method($model, $transaction);
        } elseif ($model instanceof \Phalconry\Aggregates\Ticket\Member) {
            $this->memberRepository->$method($model, $transaction);
        } elseif ($model instanceof \Phalconry\Aggregates\Ticket\Order) {
            $this->orderRepository->$method($model, $transaction);
        } elseif ($model instanceof \Phalconry\Aggregates\Ticket\Store) {
            $this->storeRepository->$method($model, $transaction);
        }
    }

    /**
     * @inheritDoc
     */
    public function delete(Aggregate $model, TransactionInterface $transaction = null)
    {
        $this->processCUD(self::METHOD_DELETE, $model, $transaction);
    }

    /**
     * @inheritDoc
     */
    public function insert(Aggregate $model, TransactionInterface $transaction = null)
    {
        $this->processCUD(self::METHOD_INSERT, $model, $transaction);
    }

    /**
     * @inheritDoc
     */
    public function update(Aggregate $model, TransactionInterface $transaction = null)
    {
        $this->processCUD(self::METHOD_UPDATE, $model, $transaction);
    }
}