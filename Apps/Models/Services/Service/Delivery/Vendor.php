<?php
namespace Phalconry\Services\Service\Delivery;


use Apps\Libraries\Log;

use Apps\Modules\Exception as VendorException;

use Phalconry\Aggregates\Aggregate;
use Phalconry\Aggregates\Delivery\ExternalProduct as ExternalProductModel;
use Phalconry\Aggregates\Delivery\ExternalVendor as ExternalVendorModel;
use Phalconry\Aggregates\Delivery\Member as MemberModel;
use Phalconry\Aggregates\Delivery\Order as OrderModel;
use Phalconry\Aggregates\Delivery\Store as StoreModel;

use Phalconry\Repositories\Repository\Delivery\ExternalVendor as ExternalVendorRepository;
use Phalconry\Repositories\Repository\Delivery\ExternalProduct as ExternalProductRepository;
use Phalconry\Repositories\Repository\Delivery\Member as MemberRepository;
use Phalconry\Repositories\Repository\Delivery\Order as OrderRepository;
use Phalconry\Repositories\Repository\Delivery\Store as StoreRepository;

use Phalcon\Mvc\Model\TransactionInterface;

/**
 * Class Vendor
 * @package Apps\Vendors\Deliveries\Services
 * @property ExternalVendorRepository $externalProductRepository
 * @property ExternalProductRepository $externalVendorRepository
 * @property MemberRepository $memberRepository
 * @property OrderRepository $orderRepository
 * @property StoreRepository $storeRepository
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
        /** @var ExternalVendorModel $externalvendorModel */
        $externalvendorModel = $this->getExternalVendorModelByExternalVendorID($storeModel->getStore()->getIsLink());

        if ($vendorCode !== $externalvendorModel->getExternalVendor()->getCode()) {
            throw new VendorException(VendorException::ERROR_CODE[905], 905);
        }

        /** @var OrderModel $orderModel */
        $orderModel = $this->getOrderModelByOrderNo($orderNo);
        if ($orderModel->getStoreID() != $storeModel->getStore()->getID()) {
            throw new VendorException(VendorException::ERROR_CODE[912], 912);
        }
        return $orderModel;
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
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
        if ($model instanceof ExternalProductModel) {
            $this->externalProductRepository->$method($model, $transaction);
        } elseif ($model instanceof ExternalVendorModel) {
            $this->externalVendorRepository->$method($model, $transaction);
        } elseif ($model instanceof MemberModel) {
            $this->memberRepository->$method($model, $transaction);
        } elseif ($model instanceof OrderModel) {
            $this->orderRepository->$method($model, $transaction);
        } elseif ($model instanceof StoreModel) {
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