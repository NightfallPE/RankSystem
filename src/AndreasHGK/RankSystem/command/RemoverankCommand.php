<?php

declare(strict_types=1);

namespace AndreasHGK\RankSystem\command;

use AndreasHGK\RankSystem\rank\RankInstance;
use AndreasHGK\RankSystem\RankSystem;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\Server;

class RemoverankCommand extends BaseCommand {

    public function __construct(Plugin $owner) {
        parent::__construct("removerank", $owner);
        $this->createPermission("ranksystem.command.removerank");
        $this->setDescription("take a rank from a player");
        $this->setUsage("/removerank <player> <rank>");
        $this->setAliases(["takerank"]);
    }

    public function onCommand(CommandSender $sender, string $commandLabel, array $args) : void {
        if(!isset($args[0])) {
            $this->sendUsage($sender);
            return;
        }

        $targetName = array_shift($args);
        $target = Server::getInstance()->getPlayer($targetName);
        //todo: offline sessions + saving

        if($target === null) {
            $sender->sendMessage("§r§c§l> §r§7The provided player could not be found.");
            return;
        }

        $sessionManager = RankSystem::getInstance()->getSessionManager();
        $targetSession = $sessionManager->getSession($target);

        if(!isset($args[0])) {
            $this->sendUsage($sender);
            return;
        }

        $rankId = array_shift($args);

        $rank = RankSystem::getInstance()->getRankManager()->get($rankId);
        if($rank === null) {
            $sender->sendMessage("§r§c§l> §r§7That rank could not be found.");
            return;
        }

        $targetSession->removeRank($rank->getId());

        $sender->sendMessage("§r§6§l> §r§7The §r§6{$rank->getName()}§r§7 rank has been removed from §r§6{$target->getName()}§r§7.");
    }

    /**
     * Send the usage message
     *
     * @param CommandSender $sender
     */
    public function sendUsage(CommandSender $sender) : void {
        $sender->sendMessage("§r§c§l> §r§7Usage: §r§6".$this->getUsage());
    }

}