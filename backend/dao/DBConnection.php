<?php
    
    
    class DBConnection {
        private $host;
        private $username;
        private $password;
        private $database;
    
        protected $connection;
    
        public function __construct(Config $config){
            
            if (!isset($this->connection)) {
                $this->host = $config->host;
                $this->username = $config->username;
                $this->password = $config->password;
                $this->database = $config->database;
                
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
        
        public function query($sql) {
            return $this->connection->query($sql);
        }
        
        public function prepare($sql) {
            return $this->connection->prepare($sql);
        }
        
        public function __destruct()
        {
            mysqli_close($this->connection);
        }
    }
?>