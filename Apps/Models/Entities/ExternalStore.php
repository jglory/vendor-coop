<?php
namespace Phalconry\Entities;


class ExternalStore extends \Phalcon\Mvc\Model
{

    /**
     * Store.ID
     * @var integer
     */
    protected $storeID;

    /**
     * Vendor.ID
     * @var integer
     */
    protected $vendorID;

    /**
     * info1
     * @var string
     */
    protected $info1;

    /**
     * info2
     * @var string
     */
    protected $info2;

    /**
     * info3
     * @var string
     */
    protected $info3;

    /**
     * 생성일
     * @var string
     */
    protected $created;

    /*---------------------------------------------------------------------------------------------------------------*/

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setWriteConnectionService('dbMaster');
        $this->setReadConnectionService('dbSlave');

        $this->belongsTo('storeID', \Phalconry\Entities\Store::class, 'ID', ['alias' => 'Store']);
        $this->belongsTo('vendorID', \Phalconry\Entities\ExternalVendor::class, 'ID', ['alias' => 'ExternalVendor']);
        $this->hasMany('storeID', \Phalconry\Entities\ExternalProduct::class, 'storeID', ['alias' => 'ExternalProduct']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'ExternalStore';
    }

    /*---------------------------------------------------------------------------------------------------------------*/

    /**
     * Method to set the value of field storeID
     *
     * @param integer $storeID
     * @return $this
     */
    public function setStoreID($storeID)
    {
        $this->storeID = $storeID;

        return $this;
    }

    /**
     * Method to set the value of field vendorID
     *
     * @param integer $vendorID
     * @return $this
     */
    public function setVendorID($vendorID)
    {
        $this->vendorID = $vendorID;

        return $this;
    }

    /**
     * Method to set the value of field info1
     *
     * @param string $info1
     * @return $this
     */
    public function setInfo1($info1)
    {
        $this->info1 = $info1;

        return $this;
    }

    /**
     * Method to set the value of field info2
     *
     * @param string $info2
     * @return $this
     */
    public function setInfo2($info2)
    {
        $this->info2 = $info2;

        return $this;
    }

    /**
     * Method to set the value of field info3
     *
     * @param string $info3
     * @return $this
     */
    public function setInfo3($info3)
    {
        $this->info3 = $info3;

        return $this;
    }

    /**
     * Method to set the value of field created
     *
     * @param string $created
     * @return $this
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Returns the value of field storeID
     *
     * @return integer
     */
    public function getStoreID()
    {
        return $this->storeID;
    }

    /**
     * Returns the value of field vendorID
     *
     * @return integer
     */
    public function getVendorID()
    {
        return $this->vendorID;
    }

    /**
     * Returns the value of field info1
     *
     * @return string
     */
    public function getInfo1()
    {
        return $this->info1;
    }

    /**
     * Returns the value of field info2
     *
     * @return string
     */
    public function getInfo2()
    {
        return $this->info2;
    }

    /**
     * Returns the value of field info3
     *
     * @return string
     */
    public function getInfo3()
    {
        return $this->info3;
    }

    /**
     * Returns the value of field created
     *
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }
}
