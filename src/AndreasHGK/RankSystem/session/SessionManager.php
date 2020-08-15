<?php

declare(strict_types=1);

namespace AndreasHGK\RankSystem\session;

use AndreasHGK\RankSystem\utils\SessionException;
use pocketmine\player\Player;

class SessionManager {

    /** @var Session[] */
    private $sessions = [];

    /**
     * Get all the currently active sessions
     *
     * @return Session[]
     */
    public function getSessions() : array {
        return $this->sessions;
    }

    /**
     * Get the session for a player
     *
     * @param Player $player
     * @return Session
     */
    public function getSession(Player $player) : Session {
        return $this->sessions[spl_object_id($player)];
    }

    /**
     * Check if a player has an active session
     *
     * @param Player $player
     * @return bool
     */
    public function hasSession(Player $player) : bool {
        return isset($this->sessions[spl_object_id($player)]);
    }

    /**
     * Create a session for a player
     *
     * @param Player $player
     * @return Session
     */
    public function createSession(Player $player) : Session {
        if($this->hasSession($player)) throw new SessionException("a session for that player already exists");
        return $this->sessions[spl_object_id($player)] = new Session($player, []);
    }

    /**
     * Remove a session for a player
     *
     * @param Player $player
     * @return Session
     */
    public function removeSession(Player $player) : void {
        if(!$this->hasSession($player)) throw new SessionException("the given player does not have an active session");
        unset($this->sessions[spl_object_id($player)]);
    }


}