CREATE TABLE `#__product_lazada` (
	`id`       INT(11)     NOT NULL AUTO_INCREMENT,
	`primaryCategory` VARCHAR(25) NOT NULL,
	`SPUId` VARCHAR(25) NULL,
	`AssociatedSku` VARCHAR(25) NULL,
	`name` VARCHAR(25) NOT NULL,
	`description` TEXT  NULL,
	`short_description` TEXT NOT NULL,
	`brand` VARCHAR(255) NOT NULL,
	`model` VARCHAR(255) NULL,
	`warranty` VARCHAR(255)  NULL,
	`warranty_type` VARCHAR(255) NULL,
	`color_family` VARCHAR(255) NULL,
	`SellerSku` VARCHAR(25) NOT NULL,
	`price` VARCHAR(25) NOT NULL,
	`quantity` VARCHAR(25) NULL,
	`special_price` VARCHAR(25) NULL,
	`special_from_date` DATETIME NOT NULL,
	`special_to_date` DATETIME NOT NULL,
	`package_height` VARCHAR(25) NOT NULL,
	`package_length` VARCHAR(25) NOT NULL,
	`package_width` VARCHAR(25) NOT NULL,
	`package_weight` VARCHAR(25) NOT NULL,
	`package_content` VARCHAR(25) NULL,
	`image` VARCHAR(255)  NULL,
	`published` TINYINT NOT NULL DEFAULT '1',
	PRIMARY KEY (`id`)
)
	ENGINE =MyISAM
	AUTO_INCREMENT =0
	DEFAULT CHARSET =utf8;