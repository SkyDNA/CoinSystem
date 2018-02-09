<?php
/**
 * Created by PhpStorm.
 * User: McpeBooster
 * Date: 09.02.2018
 * Time: 19:21
 */

namespace CoinSystem\Commands;

use CoinSystem\CoinSystem;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class CommandCoins extends Command {

    public function __construct() {
        parent::__construct("coins", "", CoinSystem::PREFIX . "by §6McpeBooster §7and §6StuckDexter§7!");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        switch ($args[0]) {
            case "get":
                if (!empty($args[1])) {
                    CoinSystem::getCoins($args[1]);
                    $sender->sendMessage(CoinSystem::PREFIX . str_replace("{coins}", $args[2], str_replace("{player}", $args[1], CoinSystem::getInstance()->getLanguage()->get("coins.get"))));
                } else {
                    $sender->sendMessage('Usage: /coins get <player>');
                }
            case "add":
                if ($sender->hasPermission("coinsystem.admin")) {
                    if (!empty($args[1]) && !empty($args[2])) {
                        CoinSystem::addCoins($args[1], intval($args[2]));
                        $sender->sendMessage(CoinSystem::PREFIX . str_replace("{coins}", $args[2], str_replace("{player}", $args[1], CoinSystem::getInstance()->getLanguage()->get("coins.add"))));
                    } else {
                        $sender->sendMessage('Usage: /coins add <player> <coins>');
                    }
                }

            case "set":
                if ($sender->hasPermission("coinsystem.admin")) {
                    if (!empty($args[1]) && !empty($args[2])) {
                        CoinSystem::setCoins($args[1], intval($args[2]));
                        $sender->sendMessage(CoinSystem::PREFIX . str_replace("{coins}", $args[2], str_replace("{player}", $args[1], CoinSystem::getInstance()->getLanguage()->get("coins.set"))));
                    } else {
                        $sender->sendMessage('Usage: /coins set <player> <coins>');
                    }
                }

            case "remove":
                if ($sender->hasPermission("coinsystem.admin")) {
                    if (!empty($args[1]) && !empty($args[2])) {
                        CoinSystem::removeCoins($args[1], intval($args[2]));
                        $sender->sendMessage(CoinSystem::PREFIX . str_replace("{coins}", $args[2], str_replace("{player}", $args[1], CoinSystem::getInstance()->getLanguage()->get("coins.remove"))));
                    } else {
                        $sender->sendMessage('Usage: /coins remove <player> <coins>');
                    }
                }

            default:
                $sender->sendMessage(CoinSystem::PREFIX . "by §6McpeBooster §7and §6StuckDexter§7!");
                $sender->sendMessage(CoinSystem::PREFIX . "type §6/coins help");
        }
    }
}