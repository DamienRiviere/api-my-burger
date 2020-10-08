<?php

namespace App\Domain\User;

use Symfony\Component\Validator\Constraints as Assert;
use App\Domain\Common\CustomValidator\UniqueEntityInput;

/**
 * Class UserDTO
 * @package App\Domain\User
 * @UniqueEntityInput(
 *     class="App\Entity\User",
 *     fields={"email"},
 *     message="Cette adresse email est déjà existante, veuillez en choisir une autre !"
 * )
 */
final class UserDTO
{

    /**
     * @var string
     * @Assert\Email(message="Veuillez entrer une adresse email valide !")
     * @Assert\NotBlank(message="Veuillez remplir le champ de l'adresse email !")
     */
    protected $email;

    /**
     * @var string
     * @Assert\Length(min="4", minMessage="Votre mot de passe ne doit pas contenir moins de 4 caractères !")
     * @Assert\NotBlank(message="Veuillez remplir le champ de mot de passe !")
     */
    protected $password;

    /**
     * @var string
     * @Assert\Type(type={"alpha"}, message="Votre prénom ne doit contenir que des lettres !")
     * @Assert\NotBlank(message="Veuillez remplir le champ du prénom !")
     */
    protected $firstName;

    /**
     * @var string
     * @Assert\Type(type={"alpha"}, message="Votre nom ne doit contenir que des lettres !")
     * @Assert\NotBlank(message="Veuillez remplir le champ du nom !")
     */
    protected $lastName;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return UserDTO
     */
    public function setEmail(string $email): UserDTO
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return UserDTO
     */
    public function setPassword(string $password): UserDTO
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return UserDTO
     */
    public function setFirstName(string $firstName): UserDTO
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return UserDTO
     */
    public function setLastName(string $lastName): UserDTO
    {
        $this->lastName = $lastName;

        return $this;
    }
}
