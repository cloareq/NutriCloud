<?php

namespace NC\AlertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Alert
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Alert
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
     * @ORM\ManyToOne(targetEntity="NC\ProBundle\Entity\Pro", inversedBy="Alert")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Pro;

    /**
     * @ORM\ManyToOne(targetEntity="NC\PatientBundle\Entity\Patient", inversedBy="Alert")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Patient;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @var integer
     *
     * @ORM\Column(name="target", type="integer")
     */
    private $target;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_saw", type="boolean")
     */
    private $is_saw;

    /**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**/
    /**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**/

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

    /**
     * @return mixed
     */
    public function getPatient()
    {
        return $this->Patient;
    }

    /**
     * @param mixed $Patient
     */
    public function setPatient($Patient)
    {
        $this->Patient = $Patient;
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
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param int $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return boolean
     */
    public function isIsSaw()
    {
        return $this->is_saw;
    }

    /**
     * @param boolean $is_saw
     */
    public function setIsSaw($is_saw)
    {
        $this->is_saw = $is_saw;
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
     * @return int
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param int $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

}
