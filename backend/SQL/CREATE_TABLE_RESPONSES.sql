CREATE TABLE `responses` (
	`survey_id` INT NOT NULL,
	`email` VARCHAR(30) NOT NULL,
	`order` INT NOT NULL,
	`value` VARCHAR,
	PRIMARY KEY (`survey_id`,`email`,`order`)
) ENGINE=InnoDB;