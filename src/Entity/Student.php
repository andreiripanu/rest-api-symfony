<?php

namespace Arcsym\RestApiSymfony\Entity;

use Arcsym\RestApiSymfony\Repository\StudentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 * @ORM\Table(name="`student`")
 */
class Student
{
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue()
   * @ORM\Column(type="integer")
   */
  private int $id;

  /**
   * @Assert\NotBlank(message="blank.lastname")
   * @Assert\Regex(pattern="/[^a-zA-z ]/", match=false, message="invalid.lastname")
   *
   * @ORM\Column(type="string")
   */
  private string $lastname;

  /**
   * @Assert\NotBlank(message="blank.firstname")
   * @Assert\Regex(pattern="/[^a-zA-z ]/", match=false, message="invalid.firstname")
   *
   * @ORM\Column(type="string")
   */
  private string $firstname;

  /**
   * @Assert\NotBlank(message="blank.gender"),
   * @Assert\Expression("value in [1, 2]", message="invalid.gender")
   *
   * @ORM\Column(type="smallint")
   */
  private string $gender;

  /**
   * @Assert\NotBlank(message="blank.email")
   * @Assert\Email(mode="html5", message="invalid.email")
   *
   * @ORM\Column(type="string")
   */
  private string $email;

  /**
   * @Assert\NotBlank(message="blank.mobile")
   * @Assert\Regex(pattern="/[^0-9]/", match=false, message="invalid.mobile")
   * @Assert\Length(min=10, max=10, exactMessage="invalid.mobile")
   *
   * @ORM\Column(type="string")
   */
  private string $mobile;

  /**
   * @Assert\NotBlank(message="blank.registration_number")
   * @Assert\Positive(message="invalid.registration_number")
   *
   * @ORM\Column(type="integer")
   */
  private int $registrationNumber;


  /**
   * @return int|null
   */
  public function getId(): ?int
  {
    return $this->id;
  }

  /**
   * @return string|null
   */
  public function getLastname(): ?string
  {
    return $this->lastname;
  }

  /**
   * @param string $lastname
   * @return $this
   */
  public function setLastname(string $lastname): self
  {
    $this->lastname = $lastname;

    return $this;
  }

  /**
   * @return string|null
   */
  public function getFirstname(): ?string
  {
    return $this->firstname;
  }

  /**
   * @param string $firstname
   * @return $this
   */
  public function setFirstname(string $firstname): self
  {
    $this->firstname = $firstname;

    return $this;
  }

  /**
   * @return string|null
   */
  public function getGender(): ?string
  {
    return $this->gender;
  }

  /**
   * @param string $gender
   * @return $this
   */
  public function setGender(string $gender): self
  {
    $this->gender = $gender;

    return $this;
  }

  /**
   * @return string|null
   */
  public function getEmail(): ?string
  {
    return $this->email;
  }

  /**
   * @param string $email
   * @return $this
   */
  public function setEmail(string $email): self
  {
    $this->email = $email;

    return $this;
  }

  /**
   * @return string|null
   */
  public function getMobile(): ?string
  {
    return $this->mobile;
  }

  /**
   * @param string $mobile
   * @return $this
   */
  public function setMobile(string $mobile): self
  {
    $this->mobile = $mobile;

    return $this;
  }

  /**
   * @return int|null
   */
  public function getRegistrationNumber(): ?int
  {
    return $this->registrationNumber;
  }

  /**
   * @param int $registrationNumber
   * @return $this
   */
  public function setRegistrationNumber(int $registrationNumber): self
  {
    $this->registrationNumber = $registrationNumber;

    return $this;
  }

  /**
   * @return array
   */
  public function objectToArray(): array
  {
    return [
      'id' => $this->id,
      'lastname' => $this->lastname,
      'firstname' => $this->firstname,
      'gender' => $this->gender,
      'email' => $this->email,
      'mobile' => $this->mobile,
      'registrationNumber' => $this->registrationNumber
    ];
  }
}
