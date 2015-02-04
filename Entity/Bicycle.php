<?php
/**
 * Created by PhpStorm.
 * User: richard
 * Date: 1/20/15
 * Time: 9:34 PM
 */

namespace SoftwareDesk\BikeTraderAPIBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SoftwareDesk\BikeTraderAPIBundle\Model\Bicycle as BaseBicycle;

/**
 * Class Bicycle
 * This class extends the BaseBicycle and provides the metadata mapping
 * for its properties and the new ones for our custom requirements.
 *
 * @package SoftwareDesk\BikeTraderAPIBundle\Entity
 *
 * @ORM\Table(name="bike_trader_bicycle")
 * @ORM\Entity(repositoryClass="SoftwareDesk\BikeTraderAPIBundle\Entity\DoctrineORMBikeTraderRepository")
 */
class Bicycle extends BaseBicycle
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    protected $name;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $type;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Bicycle
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Bicycle
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Bicycle
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
}
