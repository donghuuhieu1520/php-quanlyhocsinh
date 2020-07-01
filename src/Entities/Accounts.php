<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Accounts
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true, nullable=false)
     */
    private $username;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $password;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;

    /**
     * @ORM\OneToOne(targetEntity="App\Entities\ACLs")
     */
    private $acl;

    /**
     * @ORM\OneToMany(targetEntity="App\Entities\ClassesToAccounts", mappedBy="account")
     */
    private $classesToAccounts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entities\StudentsToRules", mappedBy="account")
     */
    private $studentsToRules;


  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @return mixed
   */
  public function getUsername()
  {
    return $this->username;
  }

  /**
   * @return mixed
   */
  public function getPassword()
  {
    return $this->password;
  }

  /**
   * @return mixed
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * @return mixed
   */
  public function getEmail()
  {
    return $this->email;
  }

  /**
   * @return mixed
   */
  public function getClassesToAccounts()
  {
    return $this->classesToAccounts;
  }

  /**
   * @param mixed $password
   * @return Accounts
   */
  public function setPassword($password)
  {
    $this->password = $password;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getAcl()
  {
    return $this->acl;
  }

  /**
   * @param mixed $acl
   */
  public function setAcl($acl): void
  {
    $this->acl = $acl;
  }

}