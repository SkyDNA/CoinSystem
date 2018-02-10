<?php
/**
 * Created by PhpStorm.
 * User: McpeBooster
 * Date: 10.02.2018
 * Time: 17:16
 */

namespace CoinSystem\Listener;

use CoinSystem\CoinSystem;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class PlayerJoinListener implements Listener {

    /**
     * @priority HIGH
     */
    public function onJoin(PlayerJoinEvent $event) {
        if(!CoinSystem::getCoins($event->getPlayer()->getName())){
            CoinSystem::addPlayer($event->getPlayer()->getName());
        }
    }


}