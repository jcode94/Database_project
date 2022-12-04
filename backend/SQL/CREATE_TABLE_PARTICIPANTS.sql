CREATE TABLE `participants` (
	`survey_id` INT NOT NULL,
	`email` VARCHAR(30) NOT NULL,
	`status` TINYINT NOT NULL DEFAULT '0',
	PRIMARY KEY (`survey_id`,`email`)
) ENGINE=InnoDB;