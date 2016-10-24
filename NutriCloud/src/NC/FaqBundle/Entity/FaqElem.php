<?php

namespace NC\FaqBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FaqElem
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="NC\FaqBundle\Entity\FaqElemRepository")
 */
class FaqElem
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
     * @ORM\Column(name="question", type="text")
     */
    private $question;
    /**
     * @var string
     *
     * @ORM\Column(name="category", type="text")
     */
    private $category;


    /**
     * @var string
     *
     * @ORM\Column(name="response", type="text")
     */
    private $response;

    /**
     * @ORM\ManyToOne(targetEntity="NC\ProBundle\Entity\Pro", inversedBy="Patient")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Pro;
    
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
     * Set question
     *
     * @param string $question
     * @return FaqElem
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    
        return $this;
    }

    /**
     * Get question
     *
     * @return string 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set response
     *
     * @param string $response
     * @return FaqElem
     */
    public function setResponse($response)
    {
        $this->response = $response;
    
        return $this;
    }

    /**
     * Get response
     *
     * @return string 
     */
    public function getResponse()
    {
        return $this->response;
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
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }
}
