<?php

namespace NC\PlanBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanAliment
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class PlanAliment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="iseaten", type="string", length=255)
     */
    private $isEaten;

    /**
     * @var string
     *
     * @ORM\Column(name="ismodem", type="string", length=255)
     */
    private $isModel;

    /**
     * @var string
     *
     * @ORM\Column(name="quantity", type="string", length=255)
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="hour", type="string", length=255)
     */
    private $hour;

    /**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**/
    /**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**/

    function __construct($name, $hour, $quantity, $is_eaten, $is_model) {
        $this->name = $name;
        $this->hour = $hour;
        $this->quantity = $quantity;
        $this->isEaten = $is_eaten;
        $this->isModel = $is_model;
    }

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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getIsEaten()
    {
        return $this->isEaten;
    }

    /**
     * @param string $isEaten
     */
    public function setIsEaten($isEaten)
    {
        $this->isEaten = $isEaten;
    }

    /**
     * @return string
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param string $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return string
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * @param string $hour
     */
    public function setHour($hour)
    {
        $this->hour = $hour;
    }

    /**
     * @return string
     */
    public function getIsModel()
    {
        return $this->isModel;
    }

    /**
     * @param string $isModel
     */
    public function setIsModel($isModel)
    {
        $this->isModel = $isModel;
    }
}