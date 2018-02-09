<?php
/**
 * Created by PhpStorm.
 * User: McpeBooster
 * Date: 09.02.2018
 * Time: 17:28
 */

namespace CoinSystem\Provider;

class MySQLDataProvider {

    public $database;

    public function __construct($host, $username, $password, $database){
        $this->database = new \mysqli($host, $username, $password, $database);

        if ($this->database->connect_error) {
            $this->plugin->getLogger()->critical("Couldn't connect to MySQL: " . $this->database->connect_error);
            return false;
        }

        $this->database->query("CREATE TABLE IF NOT EXISTS `coinsystem` (
            name VARCHAR(64) PRIMARY KEY,
			coins INT(32) NOT NULL
		)");
    }

    public function addPlayer(string $name) {
        $name = trim(strtolower($name));
        $this->database->query("INSERT INTO coinsystem (name, coins) VALUES ('" . $name . "', '0')");
        return true;
    }

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

    public function setCoins(string $name, int $coins) {
        return $this->database->query("UPDATE coinsystem SET coins = '$coins' WHERE name = '$name'");
    }

    public function addCoins(string $name, int $coins) {
        $newcoins = $coins + $this->getCoins($name);
        return $this->database->query("UPDATE coinsystem SET coins = '$newcoins' WHERE name = '$name'");
    }

    public function removeCoins(string $name, int $coins) {
        $newcoins = $coins - $this->getCoins($name);
        return $this->database->query("UPDATE coinsystem SET coins = '$newcoins' WHERE name = '$name'");
    }
    public function close() {
        $this->database->close();
    }
}