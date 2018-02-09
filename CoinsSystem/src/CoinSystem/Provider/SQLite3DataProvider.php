<?php

namespace CoinSystem\Provider;

class SQLite3DataProvider {

    public $sqlite3;

    public function __construct(string $path) {

        define('SQLITE3_OPEN_SHAREDCACHE', 0x00020000);
        if (!file_exists($path . "/players.db")) {
            $this->sqlite3 = new \SQLite3($path . "/players.db", SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE | SQLITE3_OPEN_SHAREDCACHE);
            $this->sqlite3->exec('CREATE TABLE coinsystem (name TEXT PRIMARY KEY, coins INTEGER NOT)');
        } else {
            $this->sqlite3 = new \SQLite3($path . "/players.db", SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_SHAREDCACHE);
        }
        $this->sqlite3->busyTimeout(5000);
    }

}