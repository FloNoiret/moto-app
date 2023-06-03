<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: "publication")]
class Post
{
    /* Relation BDD avec Doctrine*/
    #[ORM\Id()]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private int $id;

    /* Validation avec Doctrine*/
    #[ORM\Column(type: "string", nullable: true, length: 180)]
    /* Validation avec Form Constraints "Assert"
    #[Assert\Length(min: 0, max: 150, minMessage: "Le titre à 0 caractères", maxMessage: "Le titre ne doit pas faire plus de 150 caractères")]
      */
    private ?string $title = NULL;

    #[ORM\Column(type: "text", nullable: false, length: 300)]

    /* Validation avec Form Constraints "Assert"
    #[Assert\NotBlank(message: "Le post ne doit pas être vide")]
    #[Assert\Length(min: 1, max: 300, minMessage: "Le contenu doit avoir au moins 1 caractère", maxMessage: "Le contenu ne doit pas faire plus de 300 caractères")]
    */
    private string $content;


    #[ORM\Column(type: "text")]
    /* Validation avec Form Constraints "Assert"
    #[Assert\NotBlank(message: "L\'URL de l'image ne doit pas être vide")]
    #[Assert\Url(message: "Il doit s\'agit d\'une adresse URL")]
    */
    private ?string $image = NULL;

    #[ORM\ManyToOne(targetEntity: "App\Entity\User", inversedBy:"posts")]
    private $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->$id = $id;
        return $this;
    }


    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }


    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }


    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /*
    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }
    */

    /**
     * Get the value of user
     */ 
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */ 
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }
}
