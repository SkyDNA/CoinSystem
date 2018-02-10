<?php
/**
 * Created by PhpStorm.
 * User: McpeBooster
 * Date: 09.02.2018
 * Time: 17:28
 */

namespace CoinSystem\Provider;

use CoinSystem\CoinSystem;

class MySQLDataProvider {

    public $database;

    /**
     * MySQLDataProvider constructor.
     * @param $host
     * @param $username
     * @param $password
     * @param $database
     */
    public function __construct($host, $username, $password, $database) {
        if($host == "example.net:3306"){
            CoinSystem::getInstance()->getLogger()->critical("Please change the host in the config.yml (mysql.host)");
            CoinSystem::getInstance()->getServer()->getPluginManager()->disablePlugin(CoinSystem::getInstance());
            return false;
        }
        if($username == "YourUser"){
            CoinSystem::getInstance()->getLogger()->critical("Please change the username in the config.yml (mysql.username)");
            CoinSystem::getInstance()->getServer()->getPluginManager()->disablePlugin(CoinSystem::getInstance());
            return false;
        }


        $this->database = new \mysqli($host, $username, $password, $database);

        if ($this->database->connect_error) {
            CoinSystem::getInstance()->getLogger()->critical("Couldn't connect to MySQL: " . $this->database->connect_error);
            CoinSystem::getInstance()->getServer()->getPluginManager()->disablePlugin(CoinSystem::getInstance());
            return false;
        }

        $this->database->query("CREATE TABLE IF NOT EXISTS `coinsystem` (
            name VARCHAR(64) PRIMARY KEY,
			coins INT(32) NOT NULL
		)");
    }

    /**
     * @param string $name
     * @return bool
     */
    public function addPlayer(string $name) {
        $name = trim(strtolower($name));
        $this->database->query("INSERT INTO coinsystem (name, coins) VALUES ('" . $name . "', '0')");
        return true;
    }

    /**
     * @param string $name
     * @return int
     */
    public function getCoins(string $name) {
        $name = trim(strtolower($name));
        $result = $this->database->query("SELECT * FROM coinsystem WHERE name = '$name'");

        if ($result instanceof \mysqli_result) {
            $data = $result->fetch_assoc();
            $result->free();
            if (isset($data["name"]) and $data["name"] === $name) {
                return $data["coins"];
            }
        }
        return 0;
    }

    /**
     * @param string $name
     * @param int $coins
     * @return bool
     */
    public function setCoins(string $name, int $coins) {
        $this->database->query("UPDATE coinsystem SET coins = '$coins' WHERE name = '$name'");
        return true;
    }

    /**
     * @param string $name
     * @param int $coins
     * @return bool
     */
    public function addCoins(string $name, int $coins) {
        $newcoins = $coins + $this->getCoins($name);
        $this->database->query("UPDATE coinsystem SET coins = '$newcoins' WHERE name = '$name'");
        return true;
    }

    /**
     * @param string $name
     * @param int $coins
     * @return bool
     */
    public function removeCoins(string $name, int $coins) {
        $newcoins = $coins - $this->getCoins($name);
        $this->database->query("UPDATE coinsystem SET coins = '$newcoins' WHERE name = '$name'");
        return true;
    }

    /**
     * @return bool
     */
    public function close() {
        $this->database->close();
        return true;
    }
}