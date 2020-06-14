<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ClassesToAccounts
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entities\Classes", inversedBy="classesToAccounts")
     * @ORM\JoinColumn(name="class_id", referencedColumnName="id", nullable=false)
     */
    private $class;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entities\Accounts", inversedBy="classesToAccounts")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=false)
     */
    private $account;

  /**
   * @return mixed
   */
  public function getClass()
  {
    return $this->class;
  }

  /**
   * @return mixed
   */
  public function getAccount()
  {
    return $this->account;
  }
}