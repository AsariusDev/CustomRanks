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
namespace AsariusDev\CustomRanks\module;

use JsonException;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class EventListener implements Listener {

    /**
     * @var PluginBase
     */
    private PluginBase $plugin;

    /**
     * @var Config
     */
    private Config $playerConfig;

    /**
     * @param PluginBase $plugin
     */
    public function __construct(PluginBase $plugin) {
        $this->plugin = $plugin;
        $this->playerConfig = new Config($plugin->getDataFolder() . "players.yml", Config::YAML);
    }


    /**
     * @param PlayerJoinEvent $event
     * @return void
     * @throws JsonException
     */
    public function onPlayerJoin(PlayerJoinEvent $event): void {
        $event->setJoinMessage("");
        $player = $event->getPlayer();
        $playerName = $player->getName();

        $playerData = $this->playerConfig->getAll();

        if (!isset($playerData[$playerName])) {
            $playerData[$playerName] = [
                "rank" => "Default"
            ];
        }
        $this->playerConfig->setAll($playerData);
        $this->playerConfig->save();
    }
}