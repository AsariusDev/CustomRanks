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
namespace AsariusDev\CustomRanks;

use AsariusDev\CustomRanks\commands\RemoveRankCommand;
use AsariusDev\CustomRanks\commands\SetRankCommand;
use AsariusDev\CustomRanks\module\EventListener;
use AsariusDev\CustomRanks\ranks\MemberRanks;
use AsariusDev\CustomRanks\ranks\module\PlayerChatRankPrefix;
use AsariusDev\CustomRanks\ranks\module\PlayerRankLogin;
use AsariusDev\CustomRanks\ranks\RankManager;
use JsonException;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener{

    /**
     * @var RankManager
     */
    private RankManager $rankManager;

    /**
     * @return void
     * @throws JsonException
     */
    public function onEnable(): void{

        $memberRanks = new MemberRanks();
        $this->rankManager = new RankManager($memberRanks, $this->getDataFolder());

        $removeRankCommand = new RemoveRankCommand($this, $this->rankManager,"removerank", 'Remove a rank from player.', "/removerank"); //placeholder usageMessage
        $setRankCommand = new SetRankCommand($this, $this->rankManager,"setrank", 'Set a rank to player.', "/setrank"); //placeholder usageMessage

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerChatRankPrefix($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerRankLogin($this), $this);

        @mkdir($this->getDataFolder());


        $this->getServer()->getCommandMap()->register("Plugin", $removeRankCommand);
        $this->getServer()->getCommandMap()->register("Plugin", $setRankCommand);

        $this->getLogger()->info("Plugin Enabled");
    }

    /**
     * @return void
     */
    public function onDisable(): void {
        $this->getLogger()->info("Plugin Disabled");
    }
}
