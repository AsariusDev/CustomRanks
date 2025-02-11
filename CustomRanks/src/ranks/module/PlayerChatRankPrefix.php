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

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\lang\Translatable;
use pocketmine\player\chat\ChatFormatter;
use pocketmine\player\chat\LegacyRawChatFormatter;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class PlayerChatRankPrefix implements Listener, ChatFormatter {

    /**
     * @var PluginBase
     */
    private PluginBase $plugin;

    /**
     * @var Config
     */
    private Config $playerDataFile;

    /**
     * @var array
     */
    private array $rankColors;

    /**
     * @var array
     */
    private array $textColors;

    /**
     * @param PluginBase $plugin
     */
    public function __construct(PluginBase $plugin) {
        $this->plugin = $plugin;
        $this->playerDataFile = new Config($this->plugin->getDataFolder() . "players.yml", Config::YAML);

        $this->rankColors = [
            "Default" => TextFormat::GRAY,
            "Donator" => TextFormat::GOLD,
            "VIP" => TextFormat::DARK_GREEN,
            "Staff" => TextFormat::DARK_RED
        ];
        $this->textColors = [
            "Default" => TextFormat::GRAY,
            "Donator" => TextFormat::GOLD,
            "VIP" => TextFormat::DARK_GREEN,
            "Staff" => TextFormat::DARK_RED
        ];
    }

    /**
     * @param PlayerChatEvent $event
     * @return void
     */
    public function onPlayerChat(PlayerChatEvent $event): void {
        $event->setFormatter($this);
    }

    /**
     * @param string $username
     * @param string $message
     * @return Translatable|string
     */
    public function format(string $username, string $message): Translatable|string {

        $player = Server::getInstance()->getPlayerExact($username);
        if ($player === null) {
            return $message;
        }

        $this->playerDataFile->reload();
        $playerData = $this->playerDataFile->get($username, []);

        $playerRank = $playerData["rank"] ?? "Default";

        $rankColor = $this->rankColors[$playerRank] ?? TextFormat::GRAY;
        $textColor = $this->textColors[$playerRank] ?? TextFormat::GRAY;
        $customNameTag = $username . $rankColor . " " . "[" . $playerRank . "]";
        $message = $textColor . $message;

        return (new LegacyRawChatFormatter("{%0} >> {%1}"))->format($customNameTag, $message);
    }
}