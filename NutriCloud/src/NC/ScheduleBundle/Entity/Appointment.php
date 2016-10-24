<?php

namespace NC\ScheduleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Appointment
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Appointment
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
     * @ORM\Column(name="comment", type="string", length=2000)
     */
    private $comment;

    /**
     * @var string
     *
     * @ORM\Column(name="start", type="string", length=255)
     */
    private $start;

    /**
     * @var string
     *
     * @ORM\Column(name="end", type="string", length=255)
     */
    private $end;

    /**
     * @ORM\ManyToOne(targetEntity="NC\PatientBundle\Entity\Patient", inversedBy="Appointment")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Patient;

    /**
     * @ORM\ManyToOne(targetEntity="NC\ProBundle\Entity\Pro", inversedBy="Appointment")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Pro;

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
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param string $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * @return string
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param string $start
     */
    public function setStart($start)
    {
        $this->start = $start;
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
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

}
