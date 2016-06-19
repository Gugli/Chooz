<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Poll
 *
 * @ORM\Table(name="poll")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PollRepository")
 */
class Poll
{
    /**
     * @var int
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
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_closed", type="boolean")
     */
    private $isClosed;

    /**
     * @ORM\OneToMany(targetEntity="Option", mappedBy="poll", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $options;
    
    public function __construct() {
        $this->options = new ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set question
     *
     * @param string $question
     *
     * @return Poll
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
     * Set token
     *
     * @param string $token
     *
     * @return Poll
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }
	

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
	
    /**
     * Set options
     *
     * @param Option[] $options
     *
     * @return Poll
     */
    public function setOptions($options)
    {
        $this->options = $options;
        foreach ($this->options as $option) {
            $option->setPoll($this);
        }
        return $this;
    }
    
    /**
     * Add option
     *
     * @param Option $option
     *
     * @return Poll
     */
    public function addOption(Option $option)
    {
        $this->options->addElement($option);
        $option->setPoll($this);
        return $this;
    }

    /**
     * Remove option
     *
     * @param Option $option
     *
     * @return Poll
     */
    public function removeOption(Option $option)
    {
        $option->setPoll(null);
        $this->addresses->removeElement($option);
        return $this;
    }
    
	
    /**
     * Get options
     *
     * @return Option[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set isClosed;
     *
     * @param bool $token
     *
     * @return Poll
     */
    public function setIsClosed($isClosed)
    {
        $this->isClosed = $isClosed;

        return $this;
    }
	

    /**
     * Get isClosed
     *
     * @return string
     */
    public function getIsClosed()
    {
        return $this->isClosed;
    }
}

