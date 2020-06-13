<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
/**
* @ORM\Entity
* @ORM\Table(name="accounts",uniqueConstraints={@ORM\UniqueConstraint(name="account_username_unique", columns={"username"})})
*/
class Accounts
{
  /** 
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue
   */
  protected $id;
  /** 
   * @ORM\Column(type="string") 
   */
  protected $username;
  /** 
   * @ORM\Column(type="string") 
   */
  protected $password;
  /** 
   * @ORM\Column(type="string") 
   */
  protected $name;
  /**
   * One accounts has many roles
   * @ORM\OneToMany(targetEntity="Roles", mappedBy="account")
   * @var Doctrine\Common\Collection\ArrayCollection
   */
  protected $roles;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username.
     *
     * @param string $username
     *
     * @return Accounts
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return Accounts
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Accounts
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add role.
     *
     * @param \App\Entities\Roles $role
     *
     * @return Accounts
     */
    public function addRole(\App\Entities\Roles $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove role.
     *
     * @param \App\Entities\Roles $role
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeRole(\App\Entities\Roles $role)
    {
        return $this->roles->removeElement($role);
    }

    /**
     * Get roles.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoles()
    {
        return $this->roles;
    }
}
