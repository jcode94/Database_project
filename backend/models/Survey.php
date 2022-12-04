<?php
    class Survey {
        public $survey_id;
        public $author
        public $title;
        public $description;
        public $startDate;
        public $endDate;
        public $numQuestions;
        public $questions;
        
        public function __construct(
            $survey_id,
            $author,
            $title,
            $description,
            $startDate,
            $endDate,
            $numQuestions,
            $questions
        ) {
            $this->emails = array($emails);
            $this->title = $title;
            $this->description = $description;
            $this->startDate = $startDate;
            $this->endDate = $endDate;
            $this->numQuestions = $numQuestions;
            $this->questions = array($questions);
        }
    }
?>