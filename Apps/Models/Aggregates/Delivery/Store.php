<?php
namespace Phalconry\Aggregates\Delivery;


use Apps\Libraries\EntityProxy;
use Phalconry\Aggregates\Aggregate;

use Phalconry\Entities\Store As StoreEntity;

class Store extends Aggregate
{
    /**
     * @var EntityProxy $store
     */
    public $store;
    /**
     * @var EntityProxy $externalStore
     */
    public $externalStore;

    /**
     * Store constructor.
     *
     * @param EntityProxy|null $store
     * @param EntityProxy|null $externalStore
     */
    public function __construct(EntityProxy $store = null, EntityProxy $externalStore = null)
    {
        $this->store = $store;
        $this->externalStore = $externalStore;
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
     *
     * @return EntityProxy|null
     */
    public function getExternalStore()
    {
        return $this->externalStore;
    }

    /**
     *
     * @param EntityProxy|null $externalStore
     * @return self
     */
    public function setExternalStore($externalStore): self
    {
        $this->externalStore = $externalStore;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            'store' => $this->store,
            'externalStore' => $this->externalStore
        ];
    }
}
