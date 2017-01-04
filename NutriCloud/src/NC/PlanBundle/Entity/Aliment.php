<?php

namespace NC\PlanBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Aliment
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Aliment
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
     * @ORM\ManyToOne(targetEntity="NC\ProBundle\Entity\Pro", inversedBy="Aliment")
     */
    private $Pro;

    /**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**/
    /**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**/

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
     * @return mixed
     */
    public function getPro()
    {
        return $this->Pro;
    }

    /**
     * @param mixed $Pro
     */
    public function setPro($Pro)
    {
        $this->Pro = $Pro;
    }


}
