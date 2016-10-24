<?php

namespace NC\PlanBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use \stdClass;

/**
 * PlanDay
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class PlanDay
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
     * @var integer
     *
     * @ORM\Column(name="extra_aliment", type="integer")
     */
    private $extraAliment;

    /**
     * @ORM\ManyToMany(targetEntity="NC\PlanBundle\Entity\PlanAliment", cascade={"persist"})
     */
    private $aliments;

    /**
     * @ORM\ManyToOne(targetEntity="NC\PatientBundle\Entity\Patient", inversedBy="plans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $patient;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="string", length=255, unique=false)
     */
    private $date;

    /**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**/
    /**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**/

    public function __construct($new_date, $new_patient)
    {
        $this->aliments = new ArrayCollection();
        $this->percentage = 0;
        $this->extraAliment = 0;
        $this->date = $new_date;
        $this->patient = $new_patient;
    }

    public function addAliments(PlanAliment $aliment)
    {
        $this->aliments[] = $aliment;
        return $this;
    }

    public function setAliments($aliments)
    {
        $this->aliments = $aliments;
        return $this;
    }

    public function removeAliments(PlanAliment $aliment)
    {
        $this->aliments->removeElement($aliment);
    }

    public function removeAllAliments()
    {
        $this->aliments = [];
    }

    /**
     * @return mixed
     */
    public function getAliments()
    {
        return $this->aliments;
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
     * @return mixed
     */
    public function getPatient()
    {
        return $this->patient;
    }

    /**
     * @param mixed $Patient
     */
    public function setPatient($Patient)
    {
        $this->patient = $Patient;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getPercentage()
    {
        return $this->percentage;
    }

    /**
     * @param mixed $percentage
     */
    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;
    }

    /**
     * @return mixed
     */
    public function getExtraAliment()
    {
        return $this->extraAliment;
    }

    /**
     * @param mixed $extraAliment
     */
    public function setExtraAliment($extraAliment)
    {
        $this->extraAliment = $extraAliment;
    }
}