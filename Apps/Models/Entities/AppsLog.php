<?php
namespace Phalconry\Entities;


class AppsLog extends \Phalcon\Mvc\Model
{
    public $ID;
    public $tag;
    public $created;
    public $type;
    public $message;
    public $context;

    /**
     * @return int
     */
    public function getID()
    {
        return $this->ID;
    }

    /**
     * @param int $ID
     * @return self
     */
    public function setID($ID)
    {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     * @return self
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * @return int
     */
    public function getCreated()
    {
        return (is_null($this->created) ? null : strtotime($this->created));
    }

    /**
     * @param int $created
     * @return self
     */
    public function setCreated($created)
    {
        $this->created = date('Y-m-d H:i:s', $created);
        return $this;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return self
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param string $context
     * @return self
     */
    public function setContext($context)
    {
        $this->context = $context;
        return $this;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setWriteConnectionService('dbMaster');
        $this->setReadConnectionService('dbSlave');

        $this->setSource('AppsLog');
    }
}