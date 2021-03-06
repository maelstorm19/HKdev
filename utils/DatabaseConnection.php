<?php

class DatabaseConnection
{
    /**
     * @var PDO
     */
    var $db;

    public function __construct(){
        $config = JsonUtils::decodeJsonFileOrFail('config/config.json');
        $dbConfig = $config -> database;

        $dbhostname = $dbConfig->database_host;

        $dbname = $dbConfig->database_name;

        $dbhost = 'mysql:host=' . $dbhostname . ';dbname=' . $dbname . ';charset=utf8';

        $dbidentifier = $dbConfig->database_user;

        $dbpw = $dbConfig->database_password;

        try {
            $this->db = new PDO($dbhost, $dbidentifier, $dbpw ,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $GLOBALS['salt'] = $config -> password_salt;
            $GLOBALS['server_root'] = $config -> server_root;

            $this->createRepositories();
        }
        catch (Exception $e){
            require_once "view/connectionError.php";
            exit;
        }

    }


    public function getDatabase(){
        return $this->db;
    }

    private function createRepositories(){
        $GLOBALS['repositories']['user'] = new UserRepository($this->db);
        $GLOBALS['repositories']['home'] = new HomeRepository($this->db, $GLOBALS['repositories']['user'] -> getUser() );
        $GLOBALS['repositories']['building'] = new BuildingRepository($this->db, $GLOBALS['repositories']['user'] -> getUser() );
        $GLOBALS['repositories']['room'] = new RoomRepository($this->db, $GLOBALS['repositories']['user'] -> getUser() );
    }



}