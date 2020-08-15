<?php

declare(strict_types=1);

namespace AndreasHGK\RankSystem\listener;

use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;

class SessionListener extends BaseListener {

    /**
     * @priority LOWEST
     * @param PlayerLoginEvent $event
     */
    public function onLogin(PlayerLoginEvent $event) : void {
        $this->plugin->getSessionManager()->createSession($event->getPlayer());
    }

    /**
     * @priority LOWEST
     * @param PlayerQuitEvent $event
     */
    public function onQuit(PlayerQuitEvent $event) : void {
        $sessionManager = $this->plugin->getSessionManager();
        if($sessionManager->hasSession($event->getPlayer())) $sessionManager->removeSession($event->getPlayer());
    }

}