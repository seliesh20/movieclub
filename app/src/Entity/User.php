<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity("email")
 * @ApiResource(
 *     normalizationContext={"groups"={"user:read"}, "swagger_definition_name"="Read"} 
 * )
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank     
     * @Assert\Email    
     * @Groups({"user:read"})   
     */
    private $email;

    /**
     * @ORM\Column(type="json")  
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")     
     * @Assert\Length(min=8)
     * @Groups({"user:write"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank  
     * @Groups({"user:read"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=MovieList::class, mappedBy="user")
     * @Groups("user:read")
     */
    private $movieLists;

    /**
     * @ORM\OneToMany(targetEntity=MovieMeetingEmail::class, mappedBy="user")
     */
    private $movieMeetingEmails;

    public function __construct()
    {
        $this->movieLists = new ArrayCollection();
        $this->movieMeetingEmails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
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
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection|MovieList[]
     */
    public function getMovieLists(): Collection
    {
        return $this->movieLists;
    }

    public function addMovieList(MovieList $movieList): self
    {
        if (!$this->movieLists->contains($movieList)) {
            $this->movieLists[] = $movieList;
            $movieList->setUser($this);
        }

        return $this;
    }

    public function removeMovieList(MovieList $movieList): self
    {
        if ($this->movieLists->removeElement($movieList)) {
            // set the owning side to null (unless already changed)
            if ($movieList->getUser() === $this) {
                $movieList->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MovieMeetingEmail[]
     */
    public function getMovieMeetingEmails(): Collection
    {
        return $this->movieMeetingEmails;
    }

    public function addMovieMeetingEmail(MovieMeetingEmail $movieMeetingEmail): self
    {
        if (!$this->movieMeetingEmails->contains($movieMeetingEmail)) {
            $this->movieMeetingEmails[] = $movieMeetingEmail;
            $movieMeetingEmail->setUser($this);
        }

        return $this;
    }

    public function removeMovieMeetingEmail(MovieMeetingEmail $movieMeetingEmail): self
    {
        if ($this->movieMeetingEmails->removeElement($movieMeetingEmail)) {
            // set the owning side to null (unless already changed)
            if ($movieMeetingEmail->getUser() === $this) {
                $movieMeetingEmail->setUser(null);
            }
        }

        return $this;
    }
    
}
