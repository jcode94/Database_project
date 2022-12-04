<?php
    require_once(__DIR__ . '/../../../config/config.php');
    
    class DBConnection {
        private $host = $db_config['host'];
        private $username = $db_config['username'];
        private $password = $db_config['password'];
        private $database = $db_config['database'];
    
        protected $connection;
    
        public function __construct(){
    
            if (!isset($this->connection)) {
    
                $this->connection = new mysqli(
                    $this->host, $this->username, $this->password, $this->database
                );
    
                if (!$this->connection) {
                    echo 'Cannot connect to database server';
                    exit;
                }            
            }    
    
            return $this->connection;
        }
        
        public function __destruct()
        {
            mysql_close($this->connection);
        }
    }
?>