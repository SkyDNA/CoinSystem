<?php
namespace CoinSystem;

use pocketmine\lang\BaseLang;
use pocketmine\plugin\PluginBase;
use CoinSystem\Commands;

class coinsystem extends PluginBase {

    const PREFIX = "§7[§eCoinSystem§7] ";

    public function onEnable() {

        $this->getLogger()->info(self::PREFIX . "by §6McpeBooster §7and §6StuckDexter§7!");
        
        new Commands();

        $this->saveDefaultConfig();

        $lang = $this->getConfig()->get("language", BaseLang::FALLBACK_LANGUAGE);
        $this->baseLang = new BaseLang($lang, $this->getFile() . "resources/");

        $this->getLogger()->info(self::PREFIX . "Language: " . $lang);
    }

    /**
     * @return BaseLang
     */
    public function getLanguage(): BaseLang {
        return $this->baseLang;
    }
    
    
}