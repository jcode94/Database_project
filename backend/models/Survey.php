<?php
    class Survey {
        
        private $emails;
        private $numQuestions;
        private $title;
        private $description;
        private $startDate;
        private $endDate;
        private $questions;
        
        public function __construct(
            $emails,
            $numQuestions,
            $title,
            $description,
            $startDate,
            $endDate,
            $questions
        ) {
            $this->emails = array($emails);
            $this->numQuestions = $numQuestions;
            $this->title = $title;
            $this->description = $description;
            $this->startDate = $startDate;
            $this->endDate = $endDate;
            $this->questions = array($questions);
        }
    }
?>