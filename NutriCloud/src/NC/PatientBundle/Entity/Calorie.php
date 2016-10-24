<?php

namespace NC\PatientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Calorie
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Calorie
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
     * @var integer
     *
     * @ORM\Column(name="calorie", type="integer")
     */
    private $calorie;

    /**
     * @ORM\ManyToOne(targetEntity="NC\PatientBundle\Entity\Patient", inversedBy="medication")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Patient;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;

    /**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**/
    /**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**/

    public function __construct()
    {
        $this->created_at = new \Datetime();
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
     * Set calorie
     *
     * @param integer $calorie
     *
     * @return Calorie
     */
    public function setCalorie($calorie)
    {
        $this->calorie = $calorie;
    
        return $this;
    }

    /**
     * Get calorie
     *
     * @return integer
     */
    public function getCalorie()
    {
        return $this->calorie;
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
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param \DateTime $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

}

