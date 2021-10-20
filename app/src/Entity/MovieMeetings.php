<?php

namespace App\Entity;

use App\Repository\MovieMeetingsRepository;
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
}
