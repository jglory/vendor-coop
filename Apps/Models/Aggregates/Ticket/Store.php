<?php
namespace Phalconry\Aggregates\Ticket;


use Apps\Libraries\EntityProxy;
use Phalconry\Aggregates\Aggregate;

use Phalconry\Entities\Store As StoreEntity;

class Store extends Aggregate
{
    /**
     * @var EntityProxy $storeProxy
     */
    public $store;
    /**
     * @var int $storeBranchID
     */
    public $storeBranchID;

    /**
     * Store constructor.
     *
     * @param EntityProxy|null $store
     */
    public function __construct(EntityProxy $store = null, int $storeBranchID = null)
    {
        $this->store = $store;
        $this->storeBranchID = $storeBranchID;
    }

    /**
     *
     * @return EntityProxy|null
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     *
     * @param EntityProxy|null $store
     * @return self
     */
    public function setStore($store): self
    {
        $this->store = $store;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getStoreBranchID()
    {
        return $this->storeBranchID;
    }

    /**
     * @param int $storeBranchID
     * @return self
     */
    public function setStoreBranchID($storeBranchID): self
    {
        $this->storeBranchID = $storeBranchID;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            'store' => $this->store,
            'storeBranchID' => $this->storeBranchID
        ];
    }
}
