<?php

namespace App\Entity;

use App\Repository\MovieMeetingEmailRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovieMeetingEmailRepository::class)
 */
class MovieMeetingEmail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $email_time;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $invitation_time;

    /**
     * @ORM\ManyToOne(targetEntity=MovieMeetings::class, inversedBy="movieMeetingEmails")
     */
    private $movie_meetings;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="movieMeetingEmails")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEmailTime(): ?\DateTimeInterface
    {
        return $this->email_time;
    }

    public function setEmailTime(?\DateTimeInterface $email_time): self
    {
        $this->email_time = $email_time;

        return $this;
    }

    public function getInvitationTime(): ?\DateTimeInterface
    {
        return $this->invitation_time;
    }

    public function setInvitationTime(?\DateTimeInterface $invitation_time): self
    {
        $this->invitation_time = $invitation_time;

        return $this;
    }

    public function getMovieMeetings(): ?MovieMeetings
    {
        return $this->movie_meetings;
    }

    public function setMovieMeetings(?MovieMeetings $movie_meetings): self
    {
        $this->movie_meetings = $movie_meetings;

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
}
