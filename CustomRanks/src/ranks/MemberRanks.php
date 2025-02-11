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

class MemberRanks {

    /**
     * @var array
     */
    private array $ranks = [];

    /**
     * @param string $player
     * @return string|null
     */
    public function getRank(string $player): ?string {
        return $this->ranks[$player] ?? null;
    }

    /**
     * @param string $player
     * @param string $rank
     * @return bool
     */
    public function hasRank(string $player, string $rank): bool {
        return isset($this->ranks[$player]) && $this->ranks[$player] === $rank;
    }

    /**
     * @param string $player
     * @param string $rank
     * @return void
     */
    public function setRank(string $player, string $rank): void {
        $this->ranks[$player] = $rank;
    }

    /**
     * @param string $player
     * @return void
     */
    public function removeRank(string $player): void {
        unset($this->ranks[$player]);
    }
}