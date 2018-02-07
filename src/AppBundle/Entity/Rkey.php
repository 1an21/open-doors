<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rkey
 *
 * @ORM\Table(name="rkey", indexes={@ORM\Index(name="id_employee", columns={"id_employee"})})
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\RkeyRepository")
 */
class Rkey
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_key", type="integer")
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


}
