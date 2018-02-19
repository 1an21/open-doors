<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lockkey
 *
 * @ORM\Table(name="lockkey", indexes={@ORM\Index(name="rkey", columns={"rkey"}), @ORM\Index(name="locks", columns={"locks"})})
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\LockkeyRepository")
 */
class Lockkey
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
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Lock")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="locks", referencedColumnName="id")
     * })
     */
    private $lock;

    /**
     * @var \AppBundle\Entity\Key
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Key")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rkey", referencedColumnName="id")
     * })
     */
    private $key;



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
     * @return Lockkey
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
     * @return Lockkey
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
}
