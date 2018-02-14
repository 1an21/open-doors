<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Employeekey
 *
 * @ORM\Table(name="employeekey", uniqueConstraints={@ORM\UniqueConstraint(name="rkey", columns={"rkey"})}, indexes={@ORM\Index(name="rkey", columns={"rkey"}), @ORM\Index(name="employee", columns={"employee"})})
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\EmployeekeyRepository")
 */
class Employeekey
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
     *   @ORM\JoinColumn(name="employee", referencedColumnName="id_empl")
     * })
     */
    private $employee;

    /**
     * @var \AppBundle\Entity\Key
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Key")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rkey", referencedColumnName="id")
     * })
     */
    private $rkey;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535)
     */
    private $description;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set employee
     *
     * @param \AppBundle\Entity\Employee $employee
     *
     * @return Employeekey
     */
    public function setEmployee(\AppBundle\Entity\Employee $employee)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee
     *
     * @return \AppBundle\Entity\Employee
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * Set rkey
     *
     * @param \AppBundle\Entity\Key $rkey
     *
     * @return Employeekey
     */
    public function setRkey(\AppBundle\Entity\Key $rkey = null)
    {
        $this->rkey = $rkey;

        return $this;
    }

    /**
     * Get rkey
     *
     * @return \AppBundle\Entity\Key
     */
    public function getRkey()
    {
        return $this->rkey;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Employeekey
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
