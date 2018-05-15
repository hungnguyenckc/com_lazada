CREATE TABLE `#__access_token` (
	`id`       INT(11)     NOT NULL AUTO_INCREMENT,
	`access_token` VARCHAR(100) NOT NULL,
	`refresh_token` VARCHAR(100) NOT NULL,
	`refresh_expires_in` VARCHAR(25) NOT NULL,
	`expires_in` VARCHAR(25) NOT NULL,
	PRIMARY KEY (`id`)
)
	ENGINE =MyISAM
	AUTO_INCREMENT =0
	DEFAULT CHARSET =utf8;

INSERT INTO `#__access_token` (`access_token`,`refresh_token`,`refresh_expires_in`,`expires_in`) VALUES
(
	'50000501d02twm144f4df0Xdht3mxVifGLp1hutjk1mvRfLPZyNq2z5sg622B', 
	'500015000021h51952aa2bZxZrZcvwjqjjItzqq9g3J0FmrAVxEaieq7phLzx',
	'1526626554',
	'1526353498'
);