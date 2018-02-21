<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Lock
 *
 * @ORM\Table(name="locks")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\LockRepository")
 */
class Lock
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
     * @var string
     *
     * @ORM\Column(name="lock_name", type="text", length=65535)
     */
    private $lock_name;
    /**
     * @var string
     *
     * @ORM\Column(name="lock_pass", type="text", length=65535)
     */
    private $lock_pass;
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
    

    /**
     * Set lockName
     *
     * @param string $lockName
     *
     * @return Lock
     */
    public function setLockName($lockName)
    {
        $this->lock_name = $lockName;

        return $this;
    }

    /**
     * Get lockName
     *
     * @return string
     */
    public function getLockName()
    {
        return $this->lock_name;
    }

    /**
     * Set lockPass
     *
     * @param string $lockPass
     *
     * @return Lock
     */
    public function setLockPass($lockPass)
    {
        $this->lock_pass = $lockPass;

        return $this;
    }

    /**
     * Get lockPass
     *
     * @return string
     */
    public function getLockPass()
    {
        return $this->lock_pass;
    }
}
