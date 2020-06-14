<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ACLs
{
    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
    private $can_create_rule;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":true})
     */
    private $can_read_rule;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
    private $can_delete_rule;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
    private $can_update_rule;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":true})
     */
    private $can_read_student;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
    private $can_create_student;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
    private $can_delete_student;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
    private $can_update_student;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entities\Accounts", inversedBy="aCLs")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=false)
     */
    private $accounts;
}