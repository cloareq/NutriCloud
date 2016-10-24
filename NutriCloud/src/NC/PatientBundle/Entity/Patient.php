<?php

namespace NC\PatientBundle\Entity;

use NC\PlanBundle\Entity\PlanDay;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use NC\ProBundle\Entity\Pro;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Patient
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="NC\PatientBundle\Entity\PatientRepository")
 */
class Patient implements UserInterface
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
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;
    
    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="passwordRecoveryHash", type="string", length=255)
     */
    private $passwordRecoveryHash;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255)
     */
    private $mail;
    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true, options={"default" : null})
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true, options={"default" : null})
     */
    private $address;

    /**
     * @var interger
     *
     * @ORM\Column(name="postcode", type="string", length=255, nullable=true, options={"default" : null})
     */
    private $postcode;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     */
    private $roles;

    /**
     * @ORM\ManyToOne(targetEntity="NC\ProBundle\Entity\Pro", inversedBy="Patient")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Pro;

    /**
     * @ORM\OneToMany(targetEntity="NC\NoteBundle\Entity\Note", mappedBy="Patient", cascade={"remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $notes;

    /**
     * @ORM\OneToMany(targetEntity="NC\PatientBundle\Entity\Goal", mappedBy="Patient", cascade={"remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $goals;

    /**
     * @ORM\OneToMany(targetEntity="NC\PatientBundle\Entity\Calorie", mappedBy="Patient", cascade={"remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $calorie;

    /**
     * @ORM\OneToMany(targetEntity="NC\PatientBundle\Entity\Medication", mappedBy="Patient", cascade={"remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $medication;

    /**
     * @ORM\OneToMany(targetEntity="NC\PatientBundle\Entity\Allergy", mappedBy="Patient", cascade={"remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $allergy;

    /**
     * @ORM\OneToMany(targetEntity="NC\MessageBundle\Entity\Message", mappedBy="Patient", cascade={"remove"})
     */
    private $Message;

    /**
     * @ORM\OneToMany(targetEntity="NC\ScheduleBundle\Entity\Appointment", mappedBy="Patient")
     * @ORM\JoinColumn(nullable=false)
     * @ORM\OrderBy({"start" = "DESC"})
     */
    private $appointment;

    /**
     * @ORM\OneToMany(targetEntity="NC\AlertBundle\Entity\Alert", mappedBy="Patient")
     * @ORM\JoinColumn(nullable=false)
     */
    private $alert;

    /**
     * @ORM\OneToMany(targetEntity="NC\PlanBundle\Entity\PlanDay", mappedBy="patient", cascade={"remove"}, cascade={"persist"})
     */
    private $plans;

    /**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**/
    /**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**/

    public function __construct()
    {
        $this->roles = array('ROLE_PATIENT');
        $this->salt = "";
        $this->notes = null;
        $this->goals = null;
        $this->medication = null;
        $this->appointment = null;
        $this->Message = null;
        $this->calorie = null;
        $this->allergy = null;
        $this->plans = new ArrayCollection();
        $this->addPlans(new PlanDay("Monday", $this));
        $this->addPlans(new PlanDay("Tuesday", $this));
        $this->addPlans(new PlanDay("Wednesday", $this));
        $this->addPlans(new PlanDay("Thursday", $this));
        $this->addPlans(new PlanDay("Friday", $this));
        $this->addPlans(new PlanDay("Saturday", $this));
        $this->addPlans(new PlanDay("Sunday", $this));
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
     * Set firstName
     *
     * @param string $firstName
     * @return Patient
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return Patient
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }
    
    public function setPro($Pro)
    {
        $this->Pro = $Pro;
        return $this;
    }

    public function getPro()
    {
        return $this->Pro;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Patient
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Patient
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Patient
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return Patient
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string 
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set roles
     *
     * @param array $roles
     * @return Patient
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return array 
     */
    public function getRoles()
    {
        return $this->roles;
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return interger
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * @param interger $postcode
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param mixed $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * @return mixed
     */
    public function getAppointment()
    {
        return $this->appointment;
    }

    /**
     * @param mixed $appointment
     */
    public function setAppointment($appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * @return mixed
     */
    public function getAppointmentByWeek($Start, $End)
    {
        $ret =  array();
        $start = new \DateTime($Start);
        $end = new \DateTime($End);
        foreach ($this->appointment as $value){
            $tmp = new \DateTime($value->getStart());
            if ($tmp >= $start && $tmp <= $end)
                $ret[] = $value;
        }
        return ($ret);
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->Message;
    }

    /**
     * @param mixed $Message
     */
    public function setMessage($Message)
    {
        $this->Message = $Message;
    }

    /**
     * @return mixed
     */
    public function getAlert()
    {
        return $this->alert;
    }

    /**
     * @param mixed $alert
     */
    public function setAlert($alert)
    {
        $this->alert = $alert;
    }

    public function addPlans(PlanDay $plan)
    {
        $this->plans[] = $plan;
        return $this;
    }

    public function removePlans(PlanDay $plan)
    {
        $this->plans->removeElement($plan);
    }

    public function getPlans()
    {
        return $this->plans;
    }

    /**
     * @return string
     */
    public function getPasswordRecoveryHash()
    {
        return $this->passwordRecoveryHash;
    }

    /**
     * @param string $passwordRecoveryHash
     */
    public function setPasswordRecoveryHash($passwordRecoveryHash)
    {
        $this->passwordRecoveryHash = $passwordRecoveryHash;
    }

    /**
     * @return mixed
     */
    public function getGoals()
    {
        return $this->goals;
    }

    /**
     * @param mixed $goals
     */
    public function setGoals($goals)
    {
        $this->goals = $goals;
    }

    /**
     * @return mixed
     */
    public function getCalorie()
    {
        return $this->calorie;
    }

    /**
     * @param mixed $calorie
     */
    public function setCalorie($calorie)
    {
        $this->calorie = $calorie;
    }

    /**
     * @return mixed
     */
    public function getMedication()
    {
        return $this->medication;
    }

    /**
     * @param mixed $medication
     */
    public function setMedication($medication)
    {
        $this->medication = $medication;
    }

    /**
     * @return mixed
     */
    public function getAllergy()
    {
        return $this->allergy;
    }

    /**
     * @param mixed $allergy
     */
    public function setAllergy($allergy)
    {
        $this->allergy = $allergy;
    }
}