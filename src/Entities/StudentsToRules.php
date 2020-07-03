<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Moment\Moment;

/**
 * @ORM\Entity
 */
class StudentsToRules
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entities\Accounts", inversedBy="studentsToRules")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id", nullable=false)
     */
    private $account;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entities\Rules", inversedBy="studentsToRules")
     * @ORM\JoinColumn(name="rule_id", referencedColumnName="id", nullable=false)
     */
    private $rule;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entities\Students", inversedBy="studentsToRules")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id", nullable=false)
     */
    private $student;

    public function setStudent(Students $student): void
    {
        $this->student = $student;
    }
    public function setAccount(Accounts $account): void
    {
        $this->account = $account;
    }
    public function setRule(Rules $rule): void
    {
        $this->rule = $rule;
    }
    public function setCreateAt($date): void
    {
        $this->created_at = new \DateTime($date);
    }
    public function getRawData()
    {
        return [
            'id' => $this->id,
            'ruleId' => $this->rule->getId(),
            'ruleName' => $this->rule->getName(),
            'isBad' => $this->rule->getIsBad(),
            'studentId' => $this->student->getId(),
            'studentName' => $this->student->getFirstName() . ' ' . $this->student->getLastName(),
            'classId' => $this->student->getClass()->getId(),
            'className' => $this->student->getClass()->getName(),
            'createdAt' => $this->created_at,
            'createdBy' => $this->account->getName()
        ];
    }

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param mixed $id
   */
  public function setId($id): void
  {
    $this->id = $id;
  }

  /**
   * @return mixed
   */
  public function getCreatedAt()
  {
    return $this->created_at;
  }
}