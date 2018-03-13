<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Key
 *
 * @ORM\Table(name="masterkey")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\MasterKeyRepository")
 */
class MasterKey
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

    

   
}
