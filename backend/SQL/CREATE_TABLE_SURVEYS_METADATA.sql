CREATE TABLE `surveys_metadata` (
	`survey_id` INT NOT NULL AUTO_INCREMENT,
	`author` VARCHAR(30) NOT NULL,
	`title` VARCHAR(30) NOT NULL,
	`description` VARCHAR(50) NOT NULL,
	`start_date` DATE NOT NULL,
	`end_date` DATE NOT NULL,
	`number_of_questions` INT NOT NULL,
	PRIMARY KEY (`survey_id`)
) ENGINE=InnoDB;