<?php
namespace CoinSystem;

use CoinSystem\Provider\MySQLDataProvider;
use pocketmine\lang\BaseLang;
use pocketmine\plugin\PluginBase;
use CoinSystem\Commands;

class CoinSystem extends PluginBase {

    const PREFIX = "§7[§eCoinSystem§7] ";

    public $provider;
    public static $instance;

    public function onEnable() {

        $this->getLogger()->info(self::PREFIX . "by §6McpeBooster §7and §6StuckDexter§7!");
        self::$instance = $this;
        
        new Commands();

        $this->saveDefaultConfig();

        $lang = $this->getConfig()->get("language", BaseLang::FALLBACK_LANGUAGE);
        $this->baseLang = new BaseLang($lang, $this->getFile() . "resources/");

        $this->getLogger()->info(self::PREFIX . "Language: " . $lang);

        if($this->getConfig()->get("provider") == "mysql"){
            $this->provider = new MySQLDataProvider($this->getConfig()->getNested("mysql.host"), $this->getConfig()->getNested("mysql.username"), $this->getConfig()->getNested("mysql.password"), $this->getConfig()->getNested("mysql.password"));
        }else{
            //Comming Soon
            return;
        }
    }

    public function onDisable(){
        if(is_null($this->provider))
            $this->provider->close();
    }

    public static function getInstance(){
        return self::$instance;
    }

    /**
     * @return BaseLang
     */
    public function getLanguage(): BaseLang {
        return $this->baseLang;
    }
    
    
}
