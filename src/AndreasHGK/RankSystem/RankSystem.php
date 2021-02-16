<?php

declare(strict_types=1);

namespace AndreasHGK\RankSystem;

use AndreasHGK\RankSystem\command\AddrankCommand;
use AndreasHGK\RankSystem\command\ListranksCommand;
use AndreasHGK\RankSystem\command\PlayerranksCommand;
use AndreasHGK\RankSystem\command\RankinfoCommand;
use AndreasHGK\RankSystem\command\RemoverankCommand;
use AndreasHGK\RankSystem\provider\RankProvider;
use AndreasHGK\RankSystem\rank\RankManager;
use AndreasHGK\RankSystem\task\RankExpireTask;
use AndreasHGK\RankSystem\task\RankSystemTask;
use AndreasHGK\RankSystem\utils\InvalidArgumentException;
use pocketmine\plugin\PluginBase;

class RankSystem extends PluginBase{

    /** @var RankSystem */
    private static $instance;

    /**
     * @return RankSystem
     */
    public static function getInstance() : RankSystem {
        return self::$instance;
    }


    /** @var RankManager */
    private RankManager $rankManager;
    /** @var RankProvider */
    private RankProvider $rankProvider;

    /**
     * @return RankManager
     */
    public function getRankManager() : RankManager {
        return $this->rankManager;
    }

    /**
     * @return RankProvider
     */
    public function getRankProvider() : RankProvider {
        return $this->rankProvider;
    }

    public function onLoad() {
        self::$instance = $this;
        $this->saveResource("ranks.yml");

        $this->rankProvider = new RankProvider();
        $this->rankManager = new RankManager();
        $this->rankManager->load();
    }

    public function onEnable() {
        $this->registerTasks();
        $this->registerListeners();
        $this->registerCommands();
    }

    /**
     * Register the tasks for the plugin
     */
    public function registerTasks() : void {
        $tasks = [
            new RankExpireTask(),
        ];
        foreach($tasks as $task) {
            if(!$task instanceof RankSystemTask) throw new InvalidArgumentException("Tasks must extend RankSystemTask");
            $this->getScheduler()->scheduleDelayedRepeatingTask($task, $task->getDelay(), $task->getRepeat());
        }
    }

    /**
     * Register the listeners for the plugin
     */
    public function registerListeners() : void {
        $listeners = [

        ];
        foreach($listeners as $listener) {
            $this->getServer()->getPluginManager()->registerEvents($listener, $this);
        }
    }

    /**
     * Register the commands for the plugin
     */
    public function registerCommands() : void {
        $this->getServer()->getCommandMap()->registerAll("ranksystem", [
            new ListranksCommand($this),
            new RankinfoCommand($this),
            new AddrankCommand($this),
            new RemoverankCommand($this),
            new PlayerranksCommand($this),
        ]);
    }

}
