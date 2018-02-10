<?php

namespace CoinSystem\Provider;

class SQLite3DataProvider {

    public $sqlite3;

    /**
     * SQLite3DataProvider constructor.
     * @param string $path
     */
    public function __construct(string $path) {

        define('SQLITE3_OPEN_SHAREDCACHE', 0x00020000);
        if (!file_exists($path . "/coinsystem.db")) {
            $this->sqlite3 = new \SQLite3($path . "/coinsystem.db", SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE | SQLITE3_OPEN_SHAREDCACHE);
            $this->sqlite3->exec('CREATE TABLE coinsystem (name TEXT PRIMARY KEY, coins INTEGER NOT)');
        } else {
            $this->sqlite3 = new \SQLite3($path . "/coinsystem.db", SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_SHAREDCACHE);
        }
        $this->sqlite3->busyTimeout(5000);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function addPlayer(string $name) {
        $name = trim(strtolower($name));
        $prepare = $this->sqlite3->prepare("INSERT INTO coinsystem (name, coins) VALUES (:name, :coins)");
        $prepare->bindValue(":name", $name, SQLITE3_TEXT);
        $prepare->bindValue(":coins", 0, SQLITE3_INTEGER);
        return true;
    }

    /**
     * @param string $name
     * @return int
     */
    public function getCoins(string $name) {
        $name = trim(strtolower($name));
        $prepare = $this->sqlite3->prepare("SELECT * FROM coinsystem WHERE name = :name");
        $prepare->bindValue(":name", $name, SQLITE3_TEXT);

        $result = $prepare->execute();
        if($result instanceof \SQLite3Result){
            $data = $result->fetchArray(SQLITE3_ASSOC);
            $result->finalize();
            if(isset($data["name"]) and $data["name"] === $name){
                unset($data["name"]);
                $prepare->close();
                return $data["coins"];
            }
        }
        $prepare->close();
        return 0;
    }

    /**
     * @param string $name
     * @param int $coins
     * @return bool
     */
    public function setCoins(string $name, int $coins) {
        $name = trim(strtolower($name));
        $prepare = $this->sqlite3->prepare("UPDATE coinsystem SET coins = :coins WHERE name = :name");
        $prepare->bindValue(":name", $name, SQLITE3_TEXT);

        $prepare->bindValue(":coins", $coins, SQLITE3_TEXT);

        $prepare->execute();
        $prepare->close();
        return true;
    }

    /**
     * @param string $name
     * @param int $coins
     * @return bool
     */
    public function addCoins(string $name, int $coins) {
        return $this->setCoins($name, $this->getCoins($name) + $coins);
    }

    /**
     * @param string $name
     * @param int $coins
     * @return bool
     */
    public function removeCoins(string $name, int $coins) {
        return $this->setCoins($name, $this->getCoins($name) - $coins);
    }

    /**
     * @return bool
     */
    public function close() {
        $this->sqlite3->close();
        return true;
    }

}