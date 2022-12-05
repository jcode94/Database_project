CREATE TABLE `questions` (
	`survey_id` INT NOT NULL,
	`order` INT NOT NULL,
	`type` TINYINT NOT NULL,
	`statement` TEXT(200) NOT NULL,
	PRIMARY KEY (`survey_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;