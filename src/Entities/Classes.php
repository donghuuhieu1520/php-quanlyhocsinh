<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Classes
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entities\Students", mappedBy="class")
     */
    private $students;

    /**
     * @ORM\OneToMany(targetEntity="App\Entities\ClassesToAccounts", mappedBy="class")
     */
    private $classesToAccounts;

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
  public function getName()
  {
    return $this->name;
  }

  /**
   * @return mixed
   */
  public function getClassesToAccounts()
  {
    return $this->classesToAccounts;
  }

  public function getRawData()
  {
    return [
        'id' => $this->getId(),
        'name' => $this->getName(),
    ];
  }
}