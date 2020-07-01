<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Rules
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $is_bad;

    /**
     * @ORM\OneToMany(targetEntity="App\Entities\StudentsToRules", mappedBy="rule")
     */
    private $studentsToRules;

  /**
   * @return mixed
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * @param mixed $name
   */
  public function setName($name): void
  {
    $this->name = $name;
  }

  /**
   * @return mixed
   */
  public function getIsBad()
  {
    return $this->is_bad;
  }
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param mixed $is_bad
   */
  public function setIsBad($is_bad): void
  {
    $this->is_bad = $is_bad;
  }

  public function getRawData()
  {
    return [
        'id' => $this->id,
        'name' => $this->name,
        'isBad' => $this->is_bad
    ];
  }
}