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
}