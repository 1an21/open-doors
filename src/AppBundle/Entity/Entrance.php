<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Lockkey
 *
 * @ORM\Table(name="entrance", indexes={@ORM\Index(name="rkey", columns={"rkey"}), @ORM\Index(name="locks", columns={"locks"})})
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\EntranceRepository")

 */
class Entrance
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Lock

     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Lock")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="locks", referencedColumnName="id")
     * })
     */
    private $lock;

    /**
     * @var \AppBundle\Entity\Key
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Key")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rkey", referencedColumnName="id")
     * })
     */
    private $key;

    /**
     * @var string
     *
     * @ORM\Column(name="entime", type="string", length=65535)
     */
    private $time;
    /**
     * @var string
     *
     * @ORM\Column(name="result", type="string")
     */
    private $result;

    /**
     * Get idSk
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lock
     *
     * @param \AppBundle\Entity\Lock $lock
     *
     * @return Entrance
     */
    public function setLock(\AppBundle\Entity\Lock $lock)
    {
        $this->lock = $lock;

        return $this;
    }

    /**
     * Get lock
     *
     * @return \AppBundle\Entity\Lock
     */
    public function getLock()
    {
        return $this->lock;
    }

    /**
     * Set key
     *
     * @param \AppBundle\Entity\Key $key
     *
     * @return Entrance
     */
    public function setKey(\AppBundle\Entity\Key $key = null)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return \AppBundle\Entity\Key
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set time
     *
     * @param string $time
     *
     * @return Entrance
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set result
     *
     * @param string $result
     *
     * @return Entrance
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get result
     *
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }
}
