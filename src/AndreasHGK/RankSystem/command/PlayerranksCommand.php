<?php

declare(strict_types=1);

namespace AndreasHGK\RankSystem\command;

use AndreasHGK\RankSystem\RankSystem;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\Server;

class PlayerranksCommand extends BaseCommand {

    public function __construct(Plugin $owner) {
        parent::__construct("playerranks", $owner);
        $this->createPermission("ranksystem.command.playerranks");
        $this->setDescription("Display a player's ranks");
        $this->setUsage("/playerranks <player>");
        $this->setAliases(["pranks"]);
    }

    public function onCommand(CommandSender $sender, string $commandLabel, array $args) : void {
        if(!isset($args[0])) {
            $sender->sendMessage("§r§c§l> §r§7Please enter a player to show ranks for.");
            return;
        }

        $targetName = implode($args);
        $target = Server::getInstance()->getPlayer($targetName);
        //todo: offline sessions + saving

        if($target === null) {
            $sender->sendMessage("§r§c§l> §r§7Please enter a player to show ranks for.");
            return;
        }

        $sessionManager = RankSystem::getInstance()->getSessionManager();
        $targetSession = $sessionManager->getSession($target);

        $str = "§r§8<--§r§6NF§r§8-->\n§r§7 §r§6{$target->getName()}§r§7's ranks:§r";

        foreach($targetSession->getRanks() as $rank) {
            $str .= "\n§r§8 - §r§7Id: §r§6{$rank->getRank()->getId()} §r§8| §r§7Expire: §r§6{$rank->getExpire()} §r§8| §r§7Persistent: §r§6".($rank->isPersistent() ? "yes" : "no");
        }

        $sender->sendMessage($str."\n§r§8§l<--++-->⛏");
    }

}