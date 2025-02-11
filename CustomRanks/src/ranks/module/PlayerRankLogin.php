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
namespace AsariusDev\CustomRanks\ranks\module;

use JsonException;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class PlayerRankLogin implements Listener {

    /**
     * @var PluginBase
     */
    private PluginBase $plugin;

    /**
     * @var Config
     */
    private Config $playerDataConfig;

    /**
     * @var Config
     */
    private Config $rankDataConfig;


    /**
     * @param PluginBase $plugin
     */
    public function __construct(PluginBase $plugin) {
        $this->plugin = $plugin;

        $this->playerDataConfig = new Config($this->plugin->getDataFolder() . "players.yml", Config::YAML, []);
        $this->rankDataConfig = new Config($this->plugin->getDataFolder() . "ranks.yml", Config::YAML, []);
    }

    /**
     * @param PlayerJoinEvent $event
     * @return void
     * @throws JsonException
     */
    public function onPlayerJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $playerName = $player->getName();

        $ranks = $this->rankDataConfig->get("ranks", []);

        $playerData = $this->playerDataConfig->get($playerName, null);

        if (!in_array($playerData["rank"], $ranks, true)) {
            $playerData["rank"] = "Default";
        }

        $this->playerDataConfig->set($playerName, $playerData);
        $this->playerDataConfig->save();
    }

}