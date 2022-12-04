<?php
    class Question {
        public $order;
        public $type;
        public $statement;
        
        public function __construct($o, $t, $s) {
            $this->$order = $o;
            $this->$type = $t;
            $this->$statement = $s;
        }
    }
?>