<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User
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
     * @ORM\Column(name="emailHash", type="string", length=255, unique=true)
     */
    private $emailHash;

    /**
     * @var int
     *
     * @ORM\Column(name="score", type="bigint")
     */
    private $score;


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
     * Set emailHash
     *
     * @param string $emailHash
     *
     * @return User
     */
    public function setEmailHash($emailHash)
    {
        $this->emailHash = $emailHash;

        return $this;
    }

    /**
     * Get emailHash
     *
     * @return string
     */
    public function getEmailHash()
    {
        return $this->emailHash;
    }

    /**
     * Set score
     *
     * @param integer $score
     *
     * @return User
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }
}

