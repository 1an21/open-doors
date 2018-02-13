<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Key
 *
 * @ORM\Table(name="rkey")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\KeyRepository")
 */
class Key
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
     * @ORM\Column(name="description", type="text", length=65535)
     */
    private $description;

    /**
     * Get idKey
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * Set description
     *
     * @param string $description
     *
     * @return Key
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
