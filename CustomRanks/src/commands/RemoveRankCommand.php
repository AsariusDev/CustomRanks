<?php


/*      _                   _
 *     / \   ___  __ _ _ __(_)_   _ ___
 *    / _ \ / __|/ _` | '__| | | | / __|
 *   / ___ \\__ \ (_| | |  | | |_| \__ \
 *  /_/   \_\___/\__,_|_|  |_|\__,_|___/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation.
 *
 * @author AsariusDev
 * @link https://github.com/AsariusDev
 *
*/

declare(strict_types=1);
namespace AsariusDev\CustomRanks\commands;

use AsariusDev\CustomRanks\ranks\RankManager;
use JsonException;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginOwned;

class RemoveRankCommand extends Command implements PluginOwned {

    /**
     * @var PluginBase
     */
    private PluginBase $plugin;

    /**
     * @var RankManager
     */
    private RankManager $rankManager;

    /**
     * @param PluginBase $plugin
     * @param RankManager $rankManager
     * @param string $name
     * @param Translatable|string $description
     * @param Translatable|string|null $usageMessage
     * @param array $aliases
     */
    public function __construct(PluginBase $plugin, RankManager $rankManager, string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []) {
        $this->setPermission("commands.removerank");
        $this->plugin = $plugin;
        $this->rankManager = $rankManager;
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return mixed
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) {

        if (!$this->testPermission($sender)){
            return false;
        }

        if (count($args) < 2){
            $sender->sendMessage($this->usageMessage);
            return false;
        }

        $playerName = $args[0];
        $rank = $args[1];

        if ($this->rankManager->removeRank($playerName, $rank)){
            $sender->sendMessage("Rank $rank removed from $playerName");
        }
        else {
            $sender->sendMessage("$playerName does not have the rank $rank");
        }
        return true;
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return $this->plugin;
    }
}