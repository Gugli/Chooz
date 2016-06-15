<?php

namespace AppBundle\Entity\Poll;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participant
 *
 * @ORM\Table(name="poll_participants")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Poll\ParticipantRepository")
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
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @var int
     *
     * @ORM\Column(name="poll_id", type="integer")
     */
    private $pollId;

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
     * @var int
     *
     * @ORM\Column(name="option_id", type="integer", nullable=true)
     */
    private $optionId;


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
     * Set userId
     *
     * @param integer $userId
     *
     * @return Participant
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set pollId
     *
     * @param integer $pollId
     *
     * @return Participant
     */
    public function setPollId($pollId)
    {
        $this->pollId = $pollId;

        return $this;
    }

    /**
     * Get pollId
     *
     * @return int
     */
    public function getPollId()
    {
        return $this->pollId;
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
     * Set optionId
     *
     * @param integer $optionId
     *
     * @return Participant
     */
    public function setOptionId($optionId)
    {
        $this->optionId = $optionId;

        return $this;
    }

    /**
     * Get optionId
     *
     * @return int
     */
    public function getOptionId()
    {
        return $this->optionId;
    }
}

