<?php
namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="roles",uniqueConstraints={@ORM\UniqueConstraint(name="role_account_class_unique", columns={"account", "class"})})
*/
class Roles
{
  /** 
   * Many instaces of role refer to one instance of account
   * @ORM\Id
   * @ORM\Column(type="integer") 
   * @ORM\ManyToOne(targetEntity="Accounts", inversedBy="roles")
   * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=false)
   * @var \App\Entities\Accounts
   */
  protected $account;
  /** 
   * Many instaces of role refer to one instance of class
   * @ORM\Id
   * @ORM\Column(type="integer") 
   * @ORM\ManyToOne(targetEntity="Classes")   
   * @ORM\JoinColumn(name="class_id", referencedColumnName="id", nullable=false)
   * @var \App\Entities\Classes
   */
  protected $class;

    /**
     * Set account.
     *
     * @param int $account
     *
     * @return Roles
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account.
     *
     * @return int
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set class.
     *
     * @param int $class
     *
     * @return Roles
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class.
     *
     * @return int
     */
    public function getClass()
    {
        return $this->class;
    }
}
