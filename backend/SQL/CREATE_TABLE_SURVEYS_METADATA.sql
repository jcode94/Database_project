CREATE TABLE `surveys_metadata` (
	`survey_id` INT NOT NULL,
	`author` VARCHAR NOT NULL,
	`title` VARCHAR NOT NULL,
	`description` VARCHAR NOT NULL,
	`start_date` DATE NOT NULL,
	`end_date` DATE NOT NULL,
	`number_of_questions` INT NOT NULL,
	FULLTEXT `author index` (`author`) USING HASH,
	PRIMARY KEY (`survey_id`)
) ENGINE=InnoDB;