<?php

namespace NC\PatientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Goal
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Goal
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
     * @ORM\Column(name="percentage", type="integer")
     */
    private $percentage;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=1024)
     */
    private $text;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="string", length=1024)
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity="NC\PatientBundle\Entity\Patient", inversedBy="Goal")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Patient;

    /**//**//**//**//**//**//**//**//**//**//**//**//**//**/

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
     * Set percentage
     *
     * @param integer $percentage
     *
     * @return Goal
     */
    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;
    
        return $this;
    }

    /**
     * Get percentage
     *
     * @return integer
     */
    public function getPercentage()
    {
        return $this->percentage;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Goal
     */
    public function setText($text)
    {
        $this->text = $text;
    
        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
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

}

