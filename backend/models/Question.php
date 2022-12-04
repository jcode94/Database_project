<?php
    class Question {
        private $number;
        private $type;
        private $statement;
        
        public function __construct($n, $t, $s) {
            $number = $n;
            $type = $t;
            $statement = $s;
        }
    }
?>