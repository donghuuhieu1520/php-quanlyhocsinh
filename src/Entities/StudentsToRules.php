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
    public function getRawData()
    {
        return [
            'rule_id' => $this->rule->getId(),
            'rule_name' => $this->rule->getName(),
            'student_id' => $this->student->getId(),
            'student_name' => $this->student->getFirstName() . ' ' . $this->student->getLastName(),
            'class_id' => $this->student->getClass()->getId(),
            'create_at' => $this->created_at,
            'create_by' => $this->account->getName()
        ];
    }
}