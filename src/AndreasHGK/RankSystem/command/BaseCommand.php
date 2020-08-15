<?php

declare(strict_types=1);

namespace AndreasHGK\RankSystem\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\plugin\PluginOwnedTrait;

abstract class BaseCommand extends Command implements PluginOwned {

    use PluginOwnedTrait;

    public function __construct(string $name, Plugin $owner) {
        parent::__construct($name);
        $this->owningPlugin = $owner;
    }

    /**
     * Create a permission for the command and add it
     *
     * @param string $perm
     * @param string $default
     * @return Permission
     */
    public function createPermission(string $perm, string $default = Permission::DEFAULT_OP) : Permission {
        $permission = new Permission($perm, $default);
        PermissionManager::getInstance()->addPermission($permission);
        $this->setPermission($perm);
        return $permission;
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return mixed|void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if(!$this->testPermission($sender)) return;
        $this->onCommand($sender, $commandLabel, $args);
    }

    abstract public function onCommand(CommandSender $sender, string $commandLabel, array $args) : void;

}