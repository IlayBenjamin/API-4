<?php

//require_once(__DIR__."/../inc/config.php");

//$db = new Database();
//echo json_encode($db->select ("Select * from users LIMIT ?", ['i', 2]));
//echo "<br>" . json_encode(explode( '/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));e

class Database {

    protected $connection = null;


    public function __construct() {
        try {
            $this->connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME);
            if ( mysqli_connect_errno() )
                throw new Exception("Could not connect to database.");
        } catch ( Exception $e ) {
            throw new Exception($e->getMessage());
        }
    }


    public function execute( $query = "", $params = [] ) {
        try {
            $stmt = $this->executeStatement($query, $params);
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            if ( !is_null($stmt) )
                return $result;
        } catch ( Exception $e ) {
            throw new Exception($e->getMessage());
        }
        return false;
    }


    private function executeStatement( $query = "", $params = [] ) {
        try {
            $stmt = $this->connection->prepare($query);
            if ( $stmt === false )
                throw new Exception("Unable to do prepared statement: " . $query);
            if ( $params ) {
                $x = array_slice($params, 1);
                $stmt->bind_param($params[0], ...$x);
            }
            $stmt->execute();
            return $stmt;
        } catch ( Exception $e ) {
            throw new Exception($e->getMessage());
        }
    }


}
