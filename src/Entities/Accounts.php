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
     * @ORM\OneToOne(targetEntity="App\Entities\ACLs", cascade={"remove"})
     */
    private $acl;

    /**
     * @ORM\OneToMany(targetEntity="App\Entities\ClassesToAccounts", mappedBy="account", cascade={"remove"})
     */
    private $classesToAccounts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entities\StudentsToRules", mappedBy="account", cascade={"remove"})
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
   * @return Accounts
   */
  public function setAcl($acl)
  {
    $this->acl = $acl;
    return $this;
  }

  /**
   * @param mixed $username
   * @return Accounts
   */
  public function setUsername($username)
  {
    $this->username = $username;
    return $this;
  }

  /**
   * @param mixed $name
   * @return Accounts
   */
  public function setName($name)
  {
    $this->name = $name;
    return $this;
  }

  /**
   * @param mixed $email
   * @return Accounts
   */
  public function setEmail($email)
  {
    $this->email = $email;
    return $this;
  }

  /**
   * @param mixed $classesToAccounts
   * @return Accounts
   */
  public function setClassesToAccounts($classesToAccounts)
  {
    $this->classesToAccounts = $classesToAccounts;
    return $this;
  }

  public function getRawData()
  {
    return [
        'id' => $this->getId(),
        'email' => $this->getEmail(),
        'username' => $this->getUsername(),
        'name' => $this->getName(),
        'managedClasses' => array_map(function ($classToAccount) {
          return $classToAccount->getClass()->getRawData();
        }, $this->getClassesToAccounts()->toArray()),
        'acls' => $this->getAcl()->getRawData()
    ];
  }

}