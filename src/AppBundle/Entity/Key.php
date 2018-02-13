<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Key
 *
<<<<<<< HEAD
 * @ORM\Table(name="rkey")
=======
 * @ORM\Table(name="rkey", indexes={@ORM\Index(name="id_employee", columns={"id_employee"})})
>>>>>>> 3bc210f76dea6c544859efa28b8f049cd025314d
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

<<<<<<< HEAD
=======
    /**
     * @var \AppBundle\Entity\Employee
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Employee")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_employee", referencedColumnName="id_empl")
     * })
     */
    private $Employee;
>>>>>>> 3bc210f76dea6c544859efa28b8f049cd025314d

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

<<<<<<< HEAD
=======
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

>>>>>>> 3bc210f76dea6c544859efa28b8f049cd025314d


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
