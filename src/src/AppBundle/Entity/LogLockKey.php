<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LogLk
 *
 * @ORM\Table(name="log_lk")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\LogRepository")
 */
class LogLockKey
{
    /**
     * @var string
     *
     * @ORM\Column(name="msg", type="string", length=255, nullable=false)
     */
    private $msg;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime", nullable=false)
     */
    private $time = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="lock_name", type="string", length=255, nullable=false)
     */
    private $lockName;

    /**
     * @var string
     *
     * @ORM\Column(name="lock_pass", type="string", length=255, nullable=false)
     */
    private $lockPass;

    /**
     * @var string
     *
     * @ORM\Column(name="tag", type="string", length=11, nullable=false)
     */
    private $tag;

    /**
     * @var string
     *
     * @ORM\Column(name="old_name", type="text", length=65535, nullable=true)
     */
    private $oldName;

    /**
     * @var string
     *
     * @ORM\Column(name="old_pass", type="text", length=65535, nullable=true)
     */
    private $oldPass;

    /**
     * @var string
     *
     * @ORM\Column(name="old_tag", type="text", length=65535, nullable=true)
     */
    private $oldTag;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set msg
     *
     * @param string $msg
     *
     * @return LogLockKey
     */
    public function setMsg($msg)
    {
        $this->msg = $msg;

        return $this;
    }

    /**
     * Get msg
     *
     * @return string
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     *
     * @return LogLockKey
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set lockName
     *
     * @param string $lockName
     *
     * @return LogLockKey
     */
    public function setLockName($lockName)
    {
        $this->lockName = $lockName;

        return $this;
    }

    /**
     * Get lockName
     *
     * @return string
     */
    public function getLockName()
    {
        return $this->lockName;
    }

    /**
     * Set lockPass
     *
     * @param string $lockPass
     *
     * @return LogLockKey
     */
    public function setLockPass($lockPass)
    {
        $this->lockPass = $lockPass;

        return $this;
    }

    /**
     * Get lockPass
     *
     * @return string
     */
    public function getLockPass()
    {
        return $this->lockPass;
    }

    /**
     * Set tag
     *
     * @param string $tag
     *
     * @return LogLockKey
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set oldName
     *
     * @param string $oldName
     *
     * @return LogLockKey
     */
    public function setOldName($oldName)
    {
        $this->oldName = $oldName;

        return $this;
    }

    /**
     * Get oldName
     *
     * @return string
     */
    public function getOldName()
    {
        return $this->oldName;
    }

    /**
     * Set oldPass
     *
     * @param string $oldPass
     *
     * @return LogLockKey
     */
    public function setOldPass($oldPass)
    {
        $this->oldPass = $oldPass;

        return $this;
    }

    /**
     * Get oldPass
     *
     * @return string
     */
    public function getOldPass()
    {
        return $this->oldPass;
    }

    /**
     * Set oldTag
     *
     * @param string $oldTag
     *
     * @return LogLockKey
     */
    public function setOldTag($oldTag)
    {
        $this->oldTag = $oldTag;

        return $this;
    }

    /**
     * Get oldTag
     *
     * @return string
     */
    public function getOldTag()
    {
        return $this->oldTag;
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
}
