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
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $tag;
    /**
<<<<<<< HEAD
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $description;
    /**
=======
>>>>>>> 2a4f54938a784374e74a61859c0a0e896994ba36
     * Get idKey
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set value
     *
     * @param string $tag
     *
     * @return Key
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }
<<<<<<< HEAD

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
=======
>>>>>>> 2a4f54938a784374e74a61859c0a0e896994ba36
}
