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
     * @var \AppBundle\Entity\Employee
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Employee")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_employee", referencedColumnName="id_empl")
     * })
     */
    private $Employee;


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
     * Set Employee
     *
     * @param \AppBundle\Entity\Employee $Employee
     *
     * @return Rkey
     */
    public function setEmployee(\AppBundle\Entity\Employee $Employee )
    {
        $this->Employee = $Employee;

        return $this;
    }

    /**
     * Get Employee
     *
     * @return \AppBundle\Entity\Employee
     */
    public function getEmployee()
    {
        return $this->Employee;
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
