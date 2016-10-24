<?php

namespace NC\NoteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Note
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Note
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text", nullable=true, options={"default" : null})
     */
    private $note;

    /**
     * @var string
     *
     * @ORM\Column(name="writer", type="text")
     */
    private $writer;

    /**
     * @var integer
     *
     * @ORM\Column(name="weight", type="integer", nullable=true, options={"default" : null})
     */
    private $weight;

    /**
     * @var integer
     *
     * @ORM\Column(name="height", type="integer", nullable=true, options={"default" : null})
     */
    private $height;

    /**
     * @ORM\ManyToOne(targetEntity="NC\PatientBundle\Entity\Patient", inversedBy="Note")
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
     * Set date
     *
     * @param \DateTime $date
     * @return Note
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set note
     *
     * @param string $note
     * @return Note
     */
    public function setNote($note)
    {
        $this->note = $note;
    
        return $this;
    }

    /**
     * Get note
     *
     * @return string 
     */
    public function getNote()
    {
        return $this->note;
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
    public function getWriter()
    {
        return $this->writer;
    }

    /**
     * @param string $writer
     */
    public function setWriter($writer)
    {
        $this->writer = $writer;
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param int $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

}
