<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class MatchResult
{
    public const WINNER_HOME = 1;
    public const WINNER_AWAY = 2;
    public const DRAW = 3;
    #[Column(type: 'integer', nullable: true)]
    private ?int $homeTeamScore;
    #[Column(type: 'integer', nullable: true)]
    private ?int $awayTeamScore;
    public function equals(MatchResult $other): bool
    {
        return (
                $this->awayTeamScore == $other->getAwayTeamScore())
            && ($this->homeTeamScore == $other->getHomeTeamScore()
            );
    }
    /**
     * @return int
     */
    public function getAwayTeamScore(): ?int
    {
        return $this->awayTeamScore;
    }
    /**
     * @param int $awayTeamScore
     */
    public function setAwayTeamScore(int $awayTeamScore): void
    {
        $this->awayTeamScore = $awayTeamScore;
    }
    /**
     * @return int|null
     */
    public function getHomeTeamScore(): ?int
    {
        return $this->homeTeamScore;
    }
    /**
     * @param int $homeTeamScore
     */
    public function setHomeTeamScore(int $homeTeamScore): void
    {
        $this->homeTeamScore = $homeTeamScore;
    }
    public function sameWinnerPredicted(MatchResult $other): bool
    {
        return $this->getResultCode() == $other->getResultCode();
    }
    public function getResultCode(): int
    {
        if ($this->awayTeamScore == $this->homeTeamScore) {
            return self::DRAW;
        }
        return $this->homeTeamScore > $this->awayTeamScore ? self::WINNER_HOME : self::WINNER_AWAY;
    }
}