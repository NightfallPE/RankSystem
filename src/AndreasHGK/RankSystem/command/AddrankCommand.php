<?php

declare(strict_types=1);

namespace AndreasHGK\RankSystem\command;

use AndreasHGK\RankSystem\rank\RankInstance;
use AndreasHGK\RankSystem\RankSystem;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\Server;

class AddrankCommand extends BaseCommand {

    public function __construct(Plugin $owner) {
        parent::__construct("addrank", $owner);
        $this->createPermission("ranksystem.command.addrank");
        $this->setDescription("give a rank to a player");
        $this->setUsage("/addrank <player> <rank> <expire> <persist>");
        $this->setAliases(["giverank"]);
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

        if(isset($args[0])) {
            $expire = -1; //todo
        }else{
            $expire = -1;
        }

        $persist = true;
        if(isset($args[1])) {
            if(strtolower($args[1]) === "false") $persist = false;
        }

        $targetSession->addRank(RankInstance::create($rank, -1, $persist));

        $sender->sendMessage("§r§6§l> §r§6{$target->getName()} §r§7has been given the §r§6{$rank->getName()}§r§7 rank that expires on §r§6never §r§7and with persist=§r§6".($persist ? "true" : "false")."§r§7.");
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