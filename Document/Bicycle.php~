<?php
/**
 * Created by PhpStorm.
 * User: richard
 * Date: 1/27/15
 * Time: 8:38 PM
 */

namespace SoftwareDesk\BikeTraderAPIBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use SoftwareDesk\BikeTraderAPIBundle\Model\Bicycle as BaseBicycle;


/**
 * Class Bicycle
 * This class extends the BaseBicycle and provides the MongoDB ODM
 * metadata mapping for its properties and the new ones for our custom
 * requirements.
 *
 * @package SoftwareDesk\BikeTraderAPIBundle\Document
 *
 * @MongoDB\Document(repositoryClass="SoftwareDesk\BikeTraderAPIBundle\Repository\DoctrineODMBikeTraderRepository")
 *
 */
class Bicycle extends BaseBicycle
{
    /**
     * @MongoDB\Id(strategy="AUTO")
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    protected $name;

    /**
     * @MongoDB\String
     */
    protected $description;

    /**
     * @MongoDB\String
     */
    protected $type;



    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return string $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set id
     *
     * @param object_id $id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}
