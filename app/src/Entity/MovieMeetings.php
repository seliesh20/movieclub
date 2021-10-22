<?php

namespace App\Entity;

use App\Repository\MovieMeetingsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovieMeetingsRepository::class)
 */
class MovieMeetings
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
    private $meeting_title;

    /**
     * @ORM\Column(type="date")
     */
    private $meeting_date;

    /**
     * @ORM\Column(type="time")
     */
    private $meeting_time;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @ORM\OneToOne(targetEntity=MovieList::class, cascade={"persist", "remove"})
     */
    private $movie_list;

    /**
     * @ORM\ManyToOne(targetEntity=MovieMeetingEmail::class, inversedBy="movie_meetings")
     */
    private $movieMeetingEmail;

    /**
     * @ORM\OneToMany(targetEntity=MovieMeetingEmail::class, mappedBy="movie_meetings")
     */
    private $movieMeetingEmails;

    public function __construct()
    {
        $this->movieMeetingEmails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMeetingTitle(): ?string
    {
        return $this->meeting_title;
    }

    public function setMeetingTitle(string $meeting_title): self
    {
        $this->meeting_title = $meeting_title;

        return $this;
    }

    public function getMeetingDate(): ?\DateTimeInterface
    {
        return $this->meeting_date;
    }

    public function setMeetingDate(\DateTimeInterface $meeting_date): self
    {
        $this->meeting_date = $meeting_date;

        return $this;
    }

    public function getMeetingTime(): ?\DateTimeInterface
    {
        return $this->meeting_time;
    }

    public function setMeetingTime(\DateTimeInterface $meeting_time): self
    {
        $this->meeting_time = $meeting_time;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getMovieList(): ?MovieList
    {
        return $this->movie_list;
    }

    public function setMovieList(?MovieList $movie_list): self
    {
        $this->movie_list = $movie_list;

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
            $movieMeetingEmail->setMovieMeetings($this);
        }

        return $this;
    }

    public function removeMovieMeetingEmail(MovieMeetingEmail $movieMeetingEmail): self
    {
        if ($this->movieMeetingEmails->removeElement($movieMeetingEmail)) {
            // set the owning side to null (unless already changed)
            if ($movieMeetingEmail->getMovieMeetings() === $this) {
                $movieMeetingEmail->setMovieMeetings(null);
            }
        }

        return $this;
    }    
}
