<?php
namespace Apps\Libraries;


use Apps\Libraries\Log;

use Phalcon\Mvc\Model\MetaData As ModelMetaData;
use Phalcon\Db\Column As DbColumn;

class EntityProxy implements \JsonSerializable
{
    const EVENT_NAME = 'EntityProxy:onEntityProxyHasChangedEntity';

    /**
     * @var \Phalcon\Mvc\ModelInterface $entity
     */
    private $entity;

    /**
     * @var bool
     */
    private $changed;

    /**
     * @var bool
     */
    private $deleted;

    /**
     * @var bool
     */
    private $inserted;

    /**
     * EntityProxy constructor.
     * @param $entity
     */
    public function __construct($entity)
    {
        $this->entity = $entity;
        $this->changed = false;
        $this->deleted = false;
        $this->inserted = false;
    }

    /**
     * @return
     */
    public final function getTargetEntity()
    {
        return $this->entity;
    }

    protected function getEventsManager()
    {
        return \Phalcon\Di::getDefault()->get('eventsManager');
    }

    public function getEventName()
    {
        return self::EVENT_NAME;
    }

    /**
     * @param $data
     */
    protected final function fireEvent($data)
    {
        // 처리 완료 이벤트 발생!
        $this->getEventsManager()->fire($this->getEventName(), $this, $data);
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return $this->getTargetEntity()->jsonSerialize();
    }

    public function getIdentity()
    {
        /** @var ModelMetaData $metaData */
        $metaData = $this->getTargetEntity()->getModelsMetaData();
        $keys = $metaData->readMetaDataIndex($this->getTargetEntity(), ModelMetaData::MODELS_PRIMARY_KEY);
        $types = $metaData->readMetaDataIndex($this->getTargetEntity(), ModelMetaData::MODELS_DATA_TYPES);
        $identity = '';
        foreach ($keys as $index => $columnName) {
            switch($types[$columnName])
            {
                case DbColumn::TYPE_BOOLEAN:
                    $identity .= ($identity==='' ? '' : '_').$this->getTargetEntity()->$columnName;
                    break;
                case DbColumn::TYPE_INTEGER:
                case DbColumn::TYPE_DECIMAL:
                case DbColumn::TYPE_FLOAT:
                case DbColumn::TYPE_DOUBLE:
                case DbColumn::TYPE_BIGINTEGER:
                    $identity .= ($identity==='' ? '' : '_').$this->getTargetEntity()->$columnName;
                    break;
                case DbColumn::TYPE_DATE:
                case DbColumn::TYPE_DATETIME:
                case DbColumn::TYPE_TIMESTAMP;
                    $identity .= ($identity==='' ? '' : '_').$this->getTargetEntity()->$columnName;
                    break;
                case DbColumn::TYPE_VARCHAR:
                case DbColumn::TYPE_CHAR:
                    $identity .= ($identity==='' ? '' : '_').$this->getTargetEntity()->$columnName;
                    break;
                case DbColumn::TYPE_TEXT:
                case DbColumn::TYPE_TINYBLOB:
                case DbColumn::TYPE_BLOB:
                case DbColumn::TYPE_MEDIUMBLOB:
                case DbColumn::TYPE_LONGBLOB:
                case DbColumn::TYPE_JSON:
                case DbColumn::TYPE_JSONB:
                    throw new \DomainException('지원하지 않는 타입입니다.', 0);
                    break;
            }
        }

        return $identity;
    }

    /**
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call(string $method, array $args)
    {
        //Log::debug(__METHOD__.' '.$method);
        if (method_exists($this->getTargetEntity(), $method)) {
            if (strlen($method)>=4) {
                $prefix = substr($method, 0, 3);
                if ($prefix === 'get' || $prefix === 'set') {
                    if ($prefix === 'set') {
                        $this->changed = true;
                        $this->fireEvent(['method' => $method, 'args' => $args]);
                    }
                    return $this->getTargetEntity()->$method(...$args);
                }
            }
        }

        throw new \BadMethodCallException('Call to undefined method EntityProxy::'.$method.'()', 0);
    }

    public function hasChanged()
    {
        return $this->changed;
    }

    public function hasDeleted()
    {
        return $this->deleted;
    }

    public function hasInserted()
    {
        return $this->inserted;
    }

    public function setChanged(bool $changed): self
    {
        $this->changed = $changed;
        return $this;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;
        return $this;
    }

    public function setInserted(bool $inserted): self
    {
        $this->inserted = $inserted;
        return $this;
    }
}