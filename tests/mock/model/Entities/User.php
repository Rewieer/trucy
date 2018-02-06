<?php
namespace Tests\Model\Entities;

/**
 * @Table(name="user")
 * @Entity(repositoryClass="Model\Repositories\UserRepository")
 */
class User {
  /**
   * @var int
   * @Id
   * @Column(name="id", type="integer")
   * @GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @Column(name="email", type="string", length=255)
   * @var string
   */
  private $email;

  /**
   * @return mixed
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @return mixed
   */
  public function getEmail() {
    return $this->email;
  }

  /**
   * @param mixed $email
   * @return User
   */
  public function setEmail($email) {
    $this->email = $email;
    return $this;
  }
}