<?php

namespace NC\ProBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use NC\FaqBundle\Entity\FaqElem;
use NC\PatientBundle\Entity\Patient;
//use Symfony\Component\Validator\Constraints\DateTime;


/**
 * Pro
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="NC\ProBundle\Entity\ProRepository")
 */
class Pro implements UserInterface
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
     * @ORM\Column(name="lastName", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    private $firstName;

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
     * @ORM\OneToMany(targetEntity="NC\PatientBundle\Entity\Patient", mappedBy="Pro", cascade={"remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $Patients;

    /**
     * @ORM\OneToMany(targetEntity="NC\FaqBundle\Entity\FaqElem", mappedBy="Pro", cascade={"remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $FaqElems;

    /**
     * @ORM\OneToMany(targetEntity="NC\ScheduleBundle\Entity\Appointment", mappedBy="Pro", cascade={"remove"})
     * @ORM\JoinColumn(nullable=false)
     * @ORM\OrderBy({"start" = "DESC"})
     */
    private $appointment;

    /**
     * @ORM\OneToMany(targetEntity="NC\PlanBundle\Entity\Aliment", mappedBy="Pro", cascade={"remove"})
     */
    private $Aliment;

    /**
     * @ORM\OneToMany(targetEntity="NC\AlertBundle\Entity\Alert", mappedBy="Pro", cascade={"remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $Alert;

    /**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**/
    /**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**/

    public function __construct()
    {
        $this->roles = array('ROLE_PRO');
        $this->lastName = "";
        $this->firstName = "";
        $this->mail = "";
        $this->salt = "";
        $this->appointment = null;
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
     * Set lastName
     *
     * @param string $lastName
     * @return Pro
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

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return Pro
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

    public function addPatient(Patient $Patient)
    {
        $this->Patients[] = $Patient;
        return $this;
    }

    public function removePatient(Patient $Patient)
    {
        $this->Patients->removeElement($Patient);
    }

    public function getPatients()
    {
        return $this->Patients;
    }

    public function addFaqElem(FaqElem $FaqElem)
    {
        $this->FaqElems[] = $FaqElem;
        return $this;
    }

    public function removeFaqElem(FaqElem $FaqElem)
    {
        $this->FaqElems->removeElement($FaqElem);
    }

    public function getFaqElems()
    {
        return $this->FaqElems;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Pro
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
     * @return Pro
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
     * @return Pro
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
     * @return Pro
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
     * @return Pro
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
     * @return mixed
     */
    public function getAppointment()
    {
        return $this->appointment;
    }

    /**
     * @return mixed
     */
    public function getAppointmentByWeek($newStart, $newEnd)
    {
        $ret =  array();
        $start = new \DateTime($newStart);
        $end = new \DateTime($newEnd);
        foreach ($this->appointment as $value){
            $tmp = new \DateTime($value->getStart());
            if ($tmp >= $start && $tmp <= $end)
                $ret[] = $value;
        }
        return ($ret);
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
    public function getAliment()
    {
        return $this->Aliment;
    }

    /**
     * @param mixed $Aliment
     */
    public function setAliment($Aliment)
    {
        $this->Aliment = $Aliment;
    }

    /**
     * @return mixed
     */
    public function getAlert()
    {
        return $this->Alert;
    }

    /**
     * @param mixed $Alert
     */
    public function setAlert($Alert)
    {
        $this->Alert = $Alert;
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

}
