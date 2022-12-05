INSERT INTO `surveys_metadata`(
    `author`,
    `title`,
    `description`,
    `start_date`,
    `end_date`,
    `number_of_questions`) VALUES (?,?,?,STR_TO_DATE(?),STR_TO_DATE(?),?);
    