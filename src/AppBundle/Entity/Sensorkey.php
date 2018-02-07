<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sensorkey
 *
 * @ORM\Table(name="sensorkey", indexes={@ORM\Index(name="id_key", columns={"id_key"}), @ORM\Index(name="id_sens", columns={"id_sens"})})
 * @ORM\Entity
 */
class Sensorkey
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_sk", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSk;

    /**
     * @var \AppBundle\Entity\Rkey
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Rkey")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_key", referencedColumnName="id_key")
     * })
     */
    private $idKey;

    /**
     * @var \AppBundle\Entity\Sensor
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Sensor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_sens", referencedColumnName="id_sens")
     * })
     */
    private $idSens;



    /**
     * Get idSk
     *
     * @return integer
     */
    public function getIdSk()
    {
        return $this->idSk;
    }

    /**
     * Set idKey
     *
     * @param \AppBundle\Entity\Rkey $idKey
     *
     * @return Sensorkey
     */
    public function setIdKey(\AppBundle\Entity\Rkey $idKey = null)
    {
        $this->idKey = $idKey;

        return $this;
    }

    /**
     * Get idKey
     *
     * @return \AppBundle\Entity\Rkey
     */
    public function getIdKey()
    {
        return $this->idKey;
    }

    /**
     * Set idSens
     *
     * @param \AppBundle\Entity\Sensor $idSens
     *
     * @return Sensorkey
     */
    public function setIdSens(\AppBundle\Entity\Sensor $idSens = null)
    {
        $this->idSens = $idSens;

        return $this;
    }

    /**
     * Get idSens
     *
     * @return \AppBundle\Entity\Sensor
     */
    public function getIdSens()
    {
        return $this->idSens;
    }
}
