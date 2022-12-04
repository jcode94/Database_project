<?php
    class DBConnection {
        private $host = 'localhost';
        private $username = 'root';
        private $password = '';
        private $database = 'survey_project_db';
    
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