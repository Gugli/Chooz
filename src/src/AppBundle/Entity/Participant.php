<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Participant
 *
 * @ORM\Table(name="participants")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ParticipantRepository")
 */
class Participant
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User",cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @Assert\Type(type="AppBundle\Entity\User")
     * @Assert\Valid()
     */
    private $user;

    /**
     * @var string
     *
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     * Only used when getting a form
     */
    private $emailClear;
    
    /**
     * @var Poll
     *
     * @ORM\ManyToOne(targetEntity="Poll", inversedBy="participants")
     * @ORM\JoinColumn(name="poll_id", referencedColumnName="id", nullable=false)
     * @Assert\Type(type="AppBundle\Entity\Poll")
     * @Assert\Valid()
     */
    private $poll;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_admin", type="boolean")
     */
    private $isAdmin;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;

    /**
     * @var Option
     *
     * @ORM\ManyToOne(targetEntity="Option")
     * @ORM\JoinColumn(name="chosen_option_id", referencedColumnName="id", nullable=true)
     * @Assert\Type(type="AppBundle\Entity\Option")
     * @Assert\Valid()
     */
    private $chosenOption;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Participant")
     * @ORM\JoinColumn(name="chosen_expert_id", referencedColumnName="id", nullable=true)
     * @Assert\Type(type="AppBundle\Entity\Participant")
     * @Assert\Valid()
     */
    private $chosenExpert;

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
     * Set user
     *
     * @param User $user
     *
     * @return Participant
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set poll
     *
     * @param Poll $poll
     *
     * @return Participant
     */
    public function setPoll($poll)
    {
        $this->poll = $poll;

        return $this;
    }

    /**
     * Get poll
     *
     * @return Poll
     */
    public function getPoll()
    {
        return $this->poll;
    }

    /**
     * Set emailClear
     *
     * @param string $emailClear
     *
     * @return Participant
     */
    public function setEmailClear($emailClear)
    {
        $this->emailClear = $emailClear;

        return $this;
    }

    /**
     * Get emailClear
     *
     * @return string
     */
    public function getEmailClear()
    {
        return $this->emailClear;
    }
    /**
     * Set isAdmin
     *
     * @param boolean $isAdmin
     *
     * @return Participant
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * Get isAdmin
     *
     * @return bool
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return Participant
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
     * Set chosen option
     *
     * @param Option $chosenOption
     *
     * @return Participant
     */
    public function setChosenOption($chosenOption)
    {
        $this->chosenOption = $chosenOption;

        return $this;
    }

    /**
     * Get chosen option 
     *
     * @return Option
     */
    public function getChosenOption()
    {
        return $this->chosenOption;
    }
	
    /**
     * Set chosen expert
     *
     * @param Participant $chosenExpert
     *
     * @return Participant
     */
    public function setChosenExpert($chosenExpert)
    {
        $this->chosenExpert = $chosenExpert;

        return $this;
    }

    /**
     * Get chosen expert
     *
     * @return Participant
     */
    public function getChosenExpert()
    {
        return $this->chosenExpert;
    }
}

