<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApplicationRepository")
 */
class Application
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "This field cannot be blank",
     *      maxMessage = "This cannot be longer than {{ limit }} characters",
     * )
     */
    private $company_name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "This field cannot be blank",
     *      maxMessage = "This field cannot be longer than {{ limit }} characters",
     * )
     */
    private $position_title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(
     *      min = 0,
     *      max = 300,
     *      minMessage = "The notes be at least {{ limit }} characters long",
     *      maxMessage = "The notes cannot be longer than {{ limit }} characters",
     * )
     */
    private $notes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Interview", mappedBy="application")
     */
    private $interviews;

    public function __construct()
    {
        $this->interviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->company_name;
    }

    public function setCompanyName(string $company_name): self
    {
        $this->company_name = $company_name;

        return $this;
    }

    public function getPositionTitle(): ?string
    {
        return $this->position_title;
    }

    public function setPositionTitle(string $position_title): self
    {
        $this->position_title = $position_title;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * @return Collection|Interview[]
     */
    public function getInterviews(): Collection
    {
        return $this->interviews;
    }

    public function addInterview(Interview $interview): self
    {
        if (!$this->interviews->contains($interview)) {
            $this->interviews[] = $interview;
            $interview->setApplication($this);
        }

        return $this;
    }

    public function removeInterview(Interview $interview): self
    {
        if ($this->interviews->contains($interview)) {
            $this->interviews->removeElement($interview);
            // set the owning side to null (unless already changed)
            if ($interview->getApplication() === $this) {
                $interview->setApplication(null);
            }
        }

        return $this;
    }
}
