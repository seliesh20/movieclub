<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MovieListRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass=MovieListRepository::class)
 * @Serializer\ExclusionPolicy("all")
 * @Hateoas\Relation(
 *      "self",
 *      href=@Hateoas\Route(
 *          "expr('api_movies_lists' ~ object.getId())"
 *      )
 * ) 
 * @ApiResource(
 *   normalizationContext={"groups"={"movie_list:read"}, "swagger_definition_name":"Read"},
 *   normalizationContext={"groups"={"movie_list:write"}, "swagger_definition_name":"Write"}
 * )
 * */
class MovieList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image_url;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $imdb_id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="movieLists")
     * @ORM\JoinColumn(nullable=false) 
     * @Groups({"movie_list:read", "movie_list:write"})
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $post_time;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }

    public function setImageUrl(string $image_url): self
    {
        $this->image_url = $image_url;

        return $this;
    }

    public function getImdbId(): ?string
    {
        return $this->imdb_id;
    }

    public function setImdbId(string $imdb_id): self
    {
        $this->imdb_id = $imdb_id;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPostTime(): ?\DateTimeInterface
    {
        return $this->post_time;
    }

    public function setPostTime(\DateTimeInterface $post_time): self
    {
        $this->post_time = $post_time;

        return $this;
    }
}
