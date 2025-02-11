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
namespace AsariusDev\CustomRanks\ranks;

use JsonException;
use pocketmine\utils\Config;

class RankManager {

    /**
     * @var MemberRanks
     */
    private MemberRanks $memberRanks;

    /**
     * @var array|mixed
     */
    private array $ranks;

    /**
     * @var Config
     */
    private Config $playerDataFile;

    /**
     * @var Config
     */
    private Config $playerRankFile;

    /**
     * @param MemberRanks $memberRanks
     * @param string $dataFolder
     * @throws JsonException
     */
    public function __construct(MemberRanks $memberRanks, string $dataFolder) {
        $this->memberRanks = $memberRanks;
        $this->playerDataFile = new Config($dataFolder . "players.yml", Config::YAML);
        $this->playerRankFile = new Config($dataFolder . "ranks.yml", Config::YAML);

        if (!$this->playerRankFile->exists("ranks")) {
            $this->playerRankFile->set("ranks", ["Default", "Donator", "VIP", "Staff"]);
            $this->playerRankFile->save();
        }

        $this->ranks = $this->playerRankFile->get("ranks", []);

    }


    /**
     * @param string $player
     * @param string $rank
     * @return bool
     * @throws JsonException
     */
    public function setRank(string $player, string $rank): bool {
        $this->ranks = $this->playerRankFile->get("ranks", []);

        if (!in_array($rank, $this->ranks, true)) {
            return false;
        }

        $playerData = $this->playerDataFile->getAll();

        if (!isset($playerData[$player])) {
            return false;
        }

        $playerData[$player]["rank"] = $rank;

        $this->playerDataFile->setAll($playerData);
        $this->playerDataFile->save();

        $this->memberRanks->setRank($player, $rank);
        return true;
    }


    /**
     * @param string $player
     * @param string $rank
     * @return bool
     * @throws JsonException
     */
    public function removeRank(string $player, string $rank): bool {

        $playerData = $this->playerDataFile->getAll();

        if (!isset($playerData[$player])) {
            return false;
        }


        if ($playerData[$player]["rank"] === $rank) {
            $playerData[$player]["rank"] = "Default";
            $this->playerDataFile->set($player, $playerData[$player]);
            $this->playerDataFile->save();
            return true;
        }
        return false;
    }

    /**
     * @param string $player
     * @return string
     */
    public function getRank(string $player): string {
        $playerData = $this->playerDataFile->get($player, []);
        return $playerData["rank"] ?? "Default";
    }
}