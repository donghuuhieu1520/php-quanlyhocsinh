<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Students
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="boolean", length=12, nullable=true)
     */
    private $gender;

    /**
     * @ORM\OneToMany(targetEntity="App\Entities\StudentsToRules", mappedBy="student")
     */
    private $studentsToRules;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entities\Classes", inversedBy="students")
     * @ORM\JoinColumn(name="class_id", referencedColumnName="id", nullable=false)
     */
    private $class;

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
  public function getFirstName()
  {
    return $this->first_name;
  }

  /**
   * @param mixed $first_name
   */
  public function setFirstName($first_name): void
  {
    $this->first_name = $first_name;
  }

  /**
   * @return mixed
   */
  public function getLastName()
  {
    return $this->last_name;
  }

  /**
   * @param mixed $last_name
   */
  public function setLastName($last_name): void
  {
    $this->last_name = $last_name;
  }

  /**
   * @return mixed
   */
  public function getClass()
  {
    return $this->class;
  }

  /**
   * @param mixed $class
   */
  public function setClass($class): void
  {
    $this->class = $class;
  }

  /**
   * @return mixed
   */
  public function getPhone()
  {
    return $this->phone;
  }

  /**
   * @param mixed $phone
   */
  public function setPhone($phone): void
  {
    $this->phone = $phone;
  }

  public function getRawData()
  {
    return [
      'id' => $this->id,
      'name' => $this->first_name . ' ' . $this->last_name,
      'firstName' => $this->first_name,
      'lastName' => $this->last_name,
      'gender' => $this->gender,
      'class' => $this->class->getName(),
      'phone' => $this->phone,
      'classId' => $this->class->getId()
    ];
  }

  /**
   * @return mixed
   */
  public function getGender()
  {
    return $this->gender;
  }

  /**
   * @param mixed $gender
   */
  public function setGender($gender): void
  {
    $this->gender = $gender;
  }
}