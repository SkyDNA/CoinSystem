<?php

namespace CoinSystem;

use CoinSystem\Commands\CommandCoins;
use CoinSystem\Provider\MySQLDataProvider;
use CoinSystem\Provider\SQLite3DataProvider;
use pocketmine\lang\BaseLang;
use pocketmine\plugin\PluginBase;

class CoinSystem extends PluginBase {

    const PREFIX = "§7[§eCoinSystem§7] ";

    public $provider;
    public static $instance;

    public function onEnable() {

        $this->getLogger()->info(self::PREFIX . "by §6McpeBooster §7and §6StuckDexter§7!");
        self::$instance = $this;

        $this->saveDefaultConfig();

        $lang = $this->getConfig()->get("language", BaseLang::FALLBACK_LANGUAGE);
        $this->baseLang = new BaseLang($lang, $this->getFile() . "resources/");

        $this->getLogger()->info(self::PREFIX . "Language: " . $lang);

        $this->getServer()->getCommandMap()->register("CoinSystem", new CommandCoins($this));

        if ($this->getConfig()->get("provider") == "mysql") {
            $this->provider = new MySQLDataProvider($this->getConfig()->getNested("mysql.host"), $this->getConfig()->getNested("mysql.username"), $this->getConfig()->getNested("mysql.password"), $this->getConfig()->getNested("mysql.password"));
        } elseif ($this->getConfig()->get("provider") == "sqlite3") {
            $this->provider = new SQLite3DataProvider($this->getConfig()->getNested("sqlite3.path"));
        } else {
            //Comming Soon
            return;
        }
    }

    public function onDisable() {
        if (is_null($this->provider))
            $this->provider->close();
    }

    /**
     * @return CoinSystem
     */
    public static function getInstance(): CoinSystem {
        return self::$instance;
    }

    /**
     * @return BaseLang
     */
    public function getLanguage(): BaseLang {
        return $this->baseLang;
    }

    //API

    /**
     * @param string $name
     * @return mixed
     */
    public static function addPlayer(string $name) {
        return self::$instance->provider->addPlayer($name);
    }

    /**
     * @param string $name
     * @return int
     */
    public static function getCoins(string $name): int {
        return self::$instance->provider->getCoins($name);
    }

    /**
     * @param string $name
     * @param int $coins
     * @return mixed
     */
    public static function setCoins(string $name, int $coins) {
        return self::$instance->provider->setCoins($name, $coins);
    }

    /**
     * @param string $name
     * @param int $coins
     * @return mixed
     */
    public static function addCoins(string $name, int $coins) {
        return self::$instance->provider->addCoins($name, $coins);
    }

    /**
     * @param string $name
     * @param int $coins
     * @return mixed
     */
    public static function removeCoins(string $name, int $coins) {
        return self::$instance->provider->removeCoins($name, $coins);
    }

}