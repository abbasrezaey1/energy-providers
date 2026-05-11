-- Optional manual migration if `lib/schema_submissions.php` cannot ALTER from PHP (restricted DB user).
-- Run in phpMyAdmin / mysql CLI against your database. Skip statements that error with "duplicate column".

ALTER TABLE `submissions` ADD COLUMN `idd` INT NULL;
ALTER TABLE `submissions` ADD COLUMN `score` DECIMAL(8,2) NOT NULL DEFAULT 0;
ALTER TABLE `submissions` ADD COLUMN `business_activeness` INT NOT NULL DEFAULT 0;
ALTER TABLE `submissions` ADD COLUMN `date` VARCHAR(64) NOT NULL DEFAULT '';
ALTER TABLE `submissions` ADD COLUMN `category` VARCHAR(128) NOT NULL DEFAULT '';
ALTER TABLE `submissions` ADD COLUMN `image_url` VARCHAR(2048) NOT NULL DEFAULT '';
ALTER TABLE `submissions` ADD COLUMN `image` VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE `submissions` ADD COLUMN `website` VARCHAR(2048) NOT NULL DEFAULT '';
ALTER TABLE `submissions` ADD COLUMN `tel` VARCHAR(128) NOT NULL DEFAULT '';
ALTER TABLE `submissions` ADD COLUMN `address` VARCHAR(512) NOT NULL DEFAULT '';
ALTER TABLE `submissions` ADD COLUMN `co2` VARCHAR(64) NOT NULL DEFAULT '';
ALTER TABLE `submissions` ADD COLUMN `nuclear_waste` VARCHAR(64) NOT NULL DEFAULT '';
ALTER TABLE `submissions` ADD COLUMN `coal` VARCHAR(64) NOT NULL DEFAULT '';
ALTER TABLE `submissions` ADD COLUMN `gas` VARCHAR(64) NOT NULL DEFAULT '';
ALTER TABLE `submissions` ADD COLUMN `nuclear` VARCHAR(64) NOT NULL DEFAULT '';
ALTER TABLE `submissions` ADD COLUMN `renewable` VARCHAR(64) NOT NULL DEFAULT '';
ALTER TABLE `submissions` ADD COLUMN `iepn` VARCHAR(64) NOT NULL DEFAULT '';

UPDATE `submissions` SET `idd` = `submission_id` WHERE (`idd` IS NULL OR `idd` = 0) AND `submission_id` IS NOT NULL;
