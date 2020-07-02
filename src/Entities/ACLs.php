<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ACLs
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
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
   * @ORM\Column(type="boolean", nullable=false, options={"default":false})
   */
  private $can_read_account;

  /**
   * @ORM\Column(type="boolean", nullable=false, options={"default":false})
   */
  private $can_create_account;

  /**
   * @ORM\Column(type="boolean", nullable=false, options={"default":false})
   */
  private $can_delete_account;

  /**
   * @ORM\Column(type="boolean", nullable=false, options={"default":false})
   */
  private $can_update_account;

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
  public function getCanCreateRule()
  {
    return $this->can_create_rule;
  }

  /**
   * @param mixed $can_create_rule
   * @return ACLs
   */
  public function setCanCreateRule($can_create_rule)
  {
    $this->can_create_rule = $can_create_rule;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getCanReadRule()
  {
    return $this->can_read_rule;
  }

  /**
   * @param mixed $can_read_rule
   * @return ACLs
   */
  public function setCanReadRule($can_read_rule)
  {
    $this->can_read_rule = $can_read_rule;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getCanDeleteRule()
  {
    return $this->can_delete_rule;
  }

  /**
   * @param mixed $can_delete_rule
   * @return ACLs
   */
  public function setCanDeleteRule($can_delete_rule)
  {
    $this->can_delete_rule = $can_delete_rule;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getCanUpdateRule()
  {
    return $this->can_update_rule;
  }

  /**
   * @param mixed $can_update_rule
   * @return ACLs
   */
  public function setCanUpdateRule($can_update_rule)
  {
    $this->can_update_rule = $can_update_rule;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getCanReadStudent()
  {
    return $this->can_read_student;
  }

  /**
   * @param mixed $can_read_student
   * @return ACLs
   */
  public function setCanReadStudent($can_read_student)
  {
    $this->can_read_student = $can_read_student;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getCanCreateStudent()
  {
    return $this->can_create_student;
  }

  /**
   * @param mixed $can_create_student
   * @return ACLs
   */
  public function setCanCreateStudent($can_create_student)
  {
    $this->can_create_student = $can_create_student;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getCanDeleteStudent()
  {
    return $this->can_delete_student;
  }

  /**
   * @param mixed $can_delete_student
   * @return ACLs
   */
  public function setCanDeleteStudent($can_delete_student)
  {
    $this->can_delete_student = $can_delete_student;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getCanUpdateStudent()
  {
    return $this->can_update_student;
  }

  /**
   * @param mixed $can_update_student
   * @return ACLs
   */
  public function setCanUpdateStudent($can_update_student)
  {
    $this->can_update_student = $can_update_student;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getCanReadAccount()
  {
    return $this->can_read_account;
  }

  /**
   * @param mixed $can_read_account
   * @return ACLs
   */
  public function setCanReadAccount($can_read_account)
  {
    $this->can_read_account = $can_read_account;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getCanCreateAccount()
  {
    return $this->can_create_account;
  }

  /**
   * @param mixed $can_create_account
   * @return ACLs
   */
  public function setCanCreateAccount($can_create_account)
  {
    $this->can_create_account = $can_create_account;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getCanDeleteAccount()
  {
    return $this->can_delete_account;
  }

  /**
   * @param mixed $can_delete_account
   * @return ACLs
   */
  public function setCanDeleteAccount($can_delete_account)
  {
    $this->can_delete_account = $can_delete_account;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getCanUpdateAccount()
  {
    return $this->can_update_account;
  }

  /**
   * @param mixed $can_update_account
   * @return ACLs
   */
  public function setCanUpdateAccount($can_update_account)
  {
    $this->can_update_account = $can_update_account;
    return $this;
  }

  public function getRawData()
  {
    return [
        'canReadRule' => $this->getCanReadRule(),
        'canCreateRule' => $this->getCanCreateRule(),
        'canUpdateRule' => $this->getCanUpdateRule(),
        'canDeleteRule' => $this->getCanDeleteRule(),
        'canReadStudent' => $this->getCanReadStudent(),
        'canCreateStudent' => $this->getCanCreateStudent(),
        'canUpdateStudent' => $this->getCanUpdateStudent(),
        'canDeleteStudent' => $this->getCanDeleteStudent(),
        'canReadAccount' => $this->getCanReadAccount(),
        'canCreateAccount' => $this->getCanCreateAccount(),
        'canUpdateAccount' => $this->getCanUpdateAccount(),
        'canDeleteAccount' => $this->getCanDeleteAccount()
    ];
  }
}