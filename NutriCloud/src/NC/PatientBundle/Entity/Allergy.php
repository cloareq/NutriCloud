<?php

namespace NC\PatientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Allergy
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Allergy
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
     * @ORM\ManyToOne(targetEntity="NC\PatientBundle\Entity\Patient", inversedBy="medication")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Patient;

    /**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**/
    /**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**/

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
     *
     * @return Allergy
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

