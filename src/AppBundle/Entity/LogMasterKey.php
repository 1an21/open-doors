<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LogMk
 *
 * @ORM\Table(name="log_mk")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\LogRepository")
 */
class LogMasterKey
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
     * @ORM\Column(name="tag_mk", type="text", length=65535, nullable=false)
     */
    private $tagMk;

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
     * @return LogMasterKey
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
     * @return LogMasterKey
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
     * Set tagMk
     *
     * @param string $tagMk
     *
     * @return LogMasterKey
     */
    public function setTagMk($tagMk)
    {
        $this->tagMk = $tagMk;

        return $this;
    }

    /**
     * Get tagMk
     *
     * @return string
     */
    public function getTagMk()
    {
        return $this->tagMk;
    }

    /**
     * Set oldTag
     *
     * @param string $oldTag
     *
     * @return LogMasterKey
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
