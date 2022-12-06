<?php
    class Question {
        public $order;
        public $type;
        public $statement;
        
        public function __construct(int $order, int $type, string $statement) {
            $this->$order = $order;
            $this->$type = $type;
            $this->$statement = $statement;
        }
    }
