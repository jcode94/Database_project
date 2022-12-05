<?php

class Report
{
    public $survey_id;
    public $title;
    public $description;
    public $startDate;
    public $endDate;
    public $questions;
    public $responses;

    public function __construct(
        $survey_id,
        $title,
        $description,
        $startDate,
        $endDate,
        $questions,
        $responses
    ) {
        $this->survey_id = $survey_id;
        $this->title = $title;
        $this->description = $description;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->questions = $questions;
        $this->responses = $responses;
    }
}
