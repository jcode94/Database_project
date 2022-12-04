<?php
    class Answer {
        public $order;
        public $value;
        
        public function __construct($order, $value) {
            $this->order = $order;
            $this->value = $value;
        }
    }
