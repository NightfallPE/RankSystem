<?php

declare(strict_types=1);

namespace AndreasHGK\RankSystem\session;

use AndreasHGK\RankSystem\rank\Rank;
use AndreasHGK\RankSystem\rank\RankInstance;
use pocketmine\player\IPlayer;

class OfflineSession {

    /** @var IPlayer */
    private $player;
    /** @var RankInstance[] */
    private $ranks = [];
    /** @var bool */
    protected $hasChanged = false;

    public function __construct(IPlayer $player, array $ranks) {
        $this->player = $player;
        $this->ranks = $ranks;
    }

    /**
     * Get the owner of the session
     *
     * @return IPlayer
     */
    public function getPlayer() : IPlayer {
        return $this->player;
    }

    /**
     * Check if the owner of the session is online
     *
     * @return bool
     */
    public function isOnline() : bool {
        return $this->player->isOnline();
    }

    /**
     * Get the ranks that the player has
     *
     * @return RankInstance[]
     */
    public function getRanks() : array {
        return $this->ranks;
    }

    /**
     * Set the ranks that the player has
     *
     * @param RankInstance[] $ranks
     */
    public function setRanks(array $ranks) : void {
        $this->ranks = $ranks;
        $this->hasChanged = true;
    }

    /**
     * Check if the player has a rank
     *
     * @param string $id
     * @return bool
     */
    public function hasRank(string $id) : bool {
        return isset($this->ranks[$id]);
    }

    /**
     * Add a rank for the player
     *
     * @param RankInstance $rank
     */
    public function addRank(RankInstance $rank) : void {
        $this->ranks[$rank->getRank()->getId()] = $rank;
        $this->hasChanged = true;
    }

    /**
     * Remove a rank from the player
     *
     * @param string $id
     */
    public function removeRank(string $id) : void {
        unset($this->ranks[$id]);
        $this->hasChanged = true;
    }

    /**
     * Check if the session has changed since it was last loaded
     *
     * @return bool
     */
    public function hasChanged() : bool {
        return $this->hasChanged;
    }

}