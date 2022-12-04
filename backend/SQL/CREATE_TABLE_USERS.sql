CREATE TABLE `users` (
    `uid` int(11) NOT NULL AUTO_INCREMENT,
    `email` varchar(30) NOT NULL,
    `password` varchar(30) NOT NULL,
PRIMARY KEY(`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;