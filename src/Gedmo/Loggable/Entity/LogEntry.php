<?php

namespace Gedmo\Loggable\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LogEntry
 *
 * @ORM\Table(name="ext_log_entries", indexes={@ORM\Index(name="log_class_lookup_idx", columns={"object_class"}), @ORM\Index(name="log_date_lookup_idx", columns={"logged_at"}), @ORM\Index(name="log_user_lookup_idx", columns={"username"}), @ORM\Index(name="log_version_lookup_idx", columns={"object_id", "object_class", "version"})})
 * @ORM\Entity(repositoryClass="Gedmo\Loggable\Entity\Repository\LogEntryRepository")
 */
class LogEntry
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=8, precision=0, scale=0, nullable=false, unique=false)
     */
    private $action;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="logged_at", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $loggedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="object_id", type="string", length=64, precision=0, scale=0, nullable=true, unique=false)
     */
    private $objectId;

    /**
     * @var string
     *
     * @ORM\Column(name="object_class", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $objectClass;

    /**
     * @var integer
     *
     * @ORM\Column(name="version", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $version;

    /**
     * @var array
     *
     * @ORM\Column(name="data", type="array", precision=0, scale=0, nullable=true, unique=false)
     */
    private $data;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, precision=0, scale=0, nullable=true, unique=false)
     */
    private $username;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return LogEntry
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLoggedAt()
    {
        return $this->loggedAt;
    }

    /**
     * @param \DateTime $loggedAt
     * @return LogEntry
     */
    public function setLoggedAt($loggedAt)
    {
        $this->loggedAt = $loggedAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * @param string $objectId
     * @return LogEntry
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;

        return $this;
    }

    /**
     * @return string
     */
    public function getObjectClass()
    {
        return $this->objectClass;
    }

    /**
     * @param string $objectClass
     * @return LogEntry
     */
    public function setObjectClass($objectClass)
    {
        $this->objectClass = $objectClass;

        return $this;
    }

    /**
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param int $version
     * @return LogEntry
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return LogEntry
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return LogEntry
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }


}

