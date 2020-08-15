<?php

declare(strict_types=1);

namespace AndreasHGK\RankSystem\command;

use AndreasHGK\RankSystem\RankSystem;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;

class RankinfoCommand extends BaseCommand {

    public function __construct(Plugin $owner) {
        parent::__construct("rankinfo", $owner);
        $this->createPermission("ranksystem.command.rankinfo");
        $this->setDescription("display info for a rank");
        $this->setUsage("/rankinfo");
    }

    public function onCommand(CommandSender $sender, string $commandLabel, array $args) : void {
        $rankManager = RankSystem::getInstance()->getRankManager();

        if(!isset($args[0])) {
            $sender->sendMessage("§r§c§l> §r§7Please enter a rank id to display info for.");
            return;
        }

        $rankId = $args[0];

        $rank = $rankManager->get($rankId);
        if($rank === null) {
            $sender->sendMessage("§r§c§l> §r§7A rank with the given ID was not found.");
            return;
        }

        $str = "§r§8<--§r§6NF§r§8-->\n§r§7 Rank §r§6{$rank->getName()}§r§7 info";

        $str .= "\n §r§l§8> §r§7ID: §r§6{$rank->getId()}";
        $str .= "\n §r§l§8> §r§7Name: §r§6{$rank->getName()}";
        $str .= "\n §r§l§8> §r§7Priority: §r§6{$rank->getPriority()}";
        $str .= "\n §r§l§8> §r§7Prefix: §r§f{$rank->getPrefix()}";

        $inh = [];
        foreach($rank->getInherit() as $inherit) {
            $inh[] = "{$inherit->getName()}";
        }

        $str .= "\n §r§l§8> §r§7Inheritance: §r§6".implode("§r§8,§r§6 ", $inh);

        $sender->sendMessage($str."\n§r§8§l<--++-->⛏");
    }

}