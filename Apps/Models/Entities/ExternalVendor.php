<?php
namespace Phalconry\Entities;

class ExternalVendor extends \Phalcon\Mvc\Model
{

    /**
     * ID
     * @var integer
     */
    protected $ID;

    /**
     * code
     * @var string
     */
    protected $code;

    /**
     * name
     * @var string
     */
    protected $name;

    /**
     * token
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $domainCode;

    /*---------------------------------------------------------------------------------------------------------------*/

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setWriteConnectionService('dbMaster');
        $this->setReadConnectionService('dbSlave');

        $this->hasMany('ID', Phalconry\Entities\ExternalStore::class, 'vendorID', ['alias' => 'Store']);
        $this->hasMany('ID', Phalconry\Entities\Store::class, 'isLink', ['alias' => 'Store']);

    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'ExternalVendor';
    }

    /*---------------------------------------------------------------------------------------------------------------*/

    /**
     * Method to set the value of field ID
     *
     * @param integer $ID
     * @return $this
     */
    public function setID($ID)
    {
        $this->ID = $ID;

        return $this;
    }

    /**
     * Method to set the value of field code
     *
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Method to set the value of field token
     *
     * @param string $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Method to set the value of field domainCode
     *
     * @param string $domainCode
     * @return $this
     */
    public function setDomainCode($domainCode)
    {
        $this->domainCode = $domainCode;

        return $this;
    }

    /**
     * Returns the value of field ID
     *
     * @return integer
     */
    public function getID()
    {
        return $this->ID;
    }

    /**
     * Returns the value of field code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Returns the value of field domainCode
     *
     * @return string
     */
    public function getDomainCode()
    {
        return $this->domainCode;
    }
}
