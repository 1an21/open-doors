<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;
/**
 * Sensor
 *
 * @ORM\Table(name="sensor")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\SensorRepository")
 */
class Sensor
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_sens", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * Get idSens
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
