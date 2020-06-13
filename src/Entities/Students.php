<?php
namespace src\Entities;
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="students")
*/
class Students
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
  protected $last_name;
  /** 
   * @ORM\Column(type="string") 
   */
  protected $fist_name;

  /**
   * many students belong to one class
   * @ORM\ManyToOne(targetEntity="Classes", inversedBy="students")
   * @ORM\JoinColumn(name="class_id", referencedColumnName="id", nullable=false)
   * @var \App\Entities\Classes
   */
  protected $class;

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
     * Set lastName.
     *
     * @param string $lastName
     *
     * @return Students
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set fistName.
     *
     * @param string $fistName
     *
     * @return Students
     */
    public function setFistName($fistName)
    {
        $this->fist_name = $fistName;

        return $this;
    }

    /**
     * Get fistName.
     *
     * @return string
     */
    public function getFistName()
    {
        return $this->fist_name;
    }

    /**
     * Set class.
     *
     * @param \src\Entities\Classes $class
     *
     * @return Students
     */
    public function setClass(\src\Entities\Classes $class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class.
     *
     * @return \src\Entities\Classes
     */
    public function getClass()
    {
        return $this->class;
    }
}
