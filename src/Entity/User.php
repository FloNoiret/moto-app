<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('username', message:"Le nom d'utilisateur est déjà pris")] 
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: "L'identifiant ne doit pas être vide")]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: "Le mot de passe ne doit pas être vide")]
    #[Assert\Length(min: 1, max: 300, minMessage: "Le mot de passe doit avoir au moins 1 caractère", maxMessage: "Le mot de passe ne doit pas faire plus de 300 caractères")]
    private ?string $password = null;

    #[Assert\NotBlank(message: "La confirmation du mot de passe ne doit pas être vide")]
    private ?string $confirm = null;

    #[ORM\OneToMany(targetEntity: "App\Entity\Post", mappedBy: "user")]
    private $posts;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this-> passwordHasher = $passwordHasher;
        $this->posts = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $this-> passwordHasher-> hashPassword($this, $password);
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Get the value of posts
     */ 
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Set the value of posts
     *
     * @return  self
     */ 
    public function setPosts($posts)
    {
        $this->posts = $posts;

        return $this;
    }

    /**
     * Get the value of confirm
     */ 
    public function getConfirm()
    {
        return $this->confirm;
    }

    /**
     * Set the value of confirm
     *
     * @return  self
     */ 
    public function setConfirm($confirm)
    {
        $this->confirm = $confirm;

        return $this;
    }
}
