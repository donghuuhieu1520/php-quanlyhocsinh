<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class StudentsToRules
{
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
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entities\Rules", inversedBy="studentsToRules")
     * @ORM\JoinColumn(name="rule_id", referencedColumnName="id", nullable=false)
     */
    private $rule;

    /**
     * @ORM\Id
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
    public function setCreateAt(): void
    {
        $this->created_at = new \DateTime("now");
    }
    public function getRawData()
    {
        return [
            'ruleId' => $this->rule->getId(),
            'ruleName' => $this->rule->getName(),
            'studentId' => $this->student->getId(),
            'studentName' => $this->student->getFirstName() . ' ' . $this->student->getLastName(),
            'classId' => $this->student->getClass()->getId(),
            'className' => $this->student->getClass()->getName(),
            'createdAt' => $this->created_at,
            'createdBy' => $this->account->getName()
        ];
    }
}