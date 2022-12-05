<?php
class Survey
{
    public $survey_id;
    public $author;
    public $title;
    public $description;
    public $startDate;
    public $endDate;
    public $numQuestions;
    public $questions;
    public $responses;

    public function __construct(
        $survey_id,
        $author,
        $title,
        $description,
        $startDate,
        $endDate,
        $numQuestions,
        $questions,
        $responses
    ) {
        $this->survey_id = $survey_id;
        $this->author = $author;
        $this->title = $title;
        $this->description = $description;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->numQuestions = $numQuestions;
        $this->questions = $questions;
        $this->responses = $responses;
    }
}
