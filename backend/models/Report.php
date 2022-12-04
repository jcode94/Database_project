<?php
    class Report {
        $report;
        
        public function __construct($surveys, $responses) {
            $this->report = array_map('map_responses', $surveys, $responses);
        }
    }
    
    function map_responses( array $surveys, array $responses): array {
        return [$surveys => $responses];
    }
?>