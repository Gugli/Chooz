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
    
    /**
     * @ORM\OneToMany(targetEntity="Participant", mappedBy="poll", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $participants;
    
    public function __construct() {
        $this->options = new ArrayCollection();
        $this->participants = new ArrayCollection();
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
        $this->options[] = $option;
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
        $this->options->removeElement($option);
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
    
    /**
     * Set participants
     *
     * @param Participant[] $options
     *
     * @return Poll
     */
    public function setParticipants($participants)
    {
        $this->participants = $participants;
        foreach ($this->participants as $participant) {
            $participant->setPoll($this);
        }
        return $this;
    }
    
    /**
     * Add participant
     *
     * @param Participant $participant
     *
     * @return Poll
     */
    public function addParticipant(Participant $participant)
    {
        $this->participants[] = $participant;
        $participant->setPoll($this);
        return $this;
    }

    /**
     * Remove participant
     *
     * @param Participant $participant
     *
     * @return Poll
     */
    public function removeParticipant(Participant $participant)
    {
        $participant->setPoll(null);
        $this->participants->removeElement($participant);
        return $this;
    }
    /**
     * Get participants
     *
     * @return Participant[]
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * Find participant from token
     *
     * @param string $token
     *
     * @return Participant
     */
    public function findParticipantFromToken($token)
    {
		foreach($this->getParticipants() as $participant) {
			if($participant->getToken() == $token) {
				return $participant;
			}
		}
        return null;
    }

}

