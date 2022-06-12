<?php

namespace App\Entity;

use App\Repository\TournamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TournamentRepository::class)]
class Tournament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\ManyToMany(targetEntity: Team::class, inversedBy: "tournaments")]
    private Collection $teams;

    #[ORM\OneToMany(mappedBy: "tournament", targetEntity: FootballMatch::class, cascade: ["persist"])]
    private Collection $matches;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $logo;

    #[ORM\Column(type: 'date', length: 255, nullable: true)]
    private \DateTime $startDate;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->teams = new ArrayCollection();
        $this->matches = new ArrayCollection();
        $this->bets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeams(array $teams): self
    {
        foreach ($teams as $team) {
            $this->addTeam($team);
        }
        return $this;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
            $team->addTournament($this);
        }

        return $this;
    }

    public function isMatchPossible(?Team $teamA, ?Team $teamB): bool
    {
        if ($teamA == null && $teamB == null) {
            return true;
        } else {
            $a = (($teamA == null || $this->teams->contains($teamA)) &&
                ($teamB == null || $this->teams->contains($teamB)));
            return $a && ($teamA->getId() !== $teamB->getId());
        }
    }

    public function getTeam(int $id): ?Team
    {
        foreach ($this->teams as $team) {
            if ($team->getId() == $id) {
                return $team;
            }
        }
        return null;
    }

    public function removeTeam(Team $team): self
    {
        $this->teams->removeElement($team);

        return $this;
    }

    /**
     * @return Collection|FootballMatch[]
     */
    public function getMatches(): Collection
    {
        return $this->matches;
    }

    public function addMatch(FootballMatch $match): self
    {
        if (!$this->matches->contains($match)) {
            $this->matches[] = $match;
            $match->setTournament($this);
        }

        return $this;
    }

    public function removeMatch(FootballMatch $match): self
    {
        if ($this->matches->removeElement($match)) {
            // set the owning side to null (unless already changed)
            if ($match->getTournament() === $this) {
                $match->setTournament(null);
            }
        }

        return $this;
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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

}
