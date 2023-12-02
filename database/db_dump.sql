-- MySQL Script generated by MySQL Workbench
-- Sat Dec  2 14:15:02 2023
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema vacation_rental_db
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema vacation_rental_db
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `vacation_rental_db` DEFAULT CHARACTER SET utf8 ;
USE `vacation_rental_db` ;

-- -----------------------------------------------------
-- Table `vacation_rental_db`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `vacation_rental_db`.`users` ;

CREATE TABLE IF NOT EXISTS `vacation_rental_db`.`users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(254) NOT NULL,
  `password` VARCHAR(64) NOT NULL,
  `forename` VARCHAR(64) NOT NULL,
  `surname` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

CREATE UNIQUE INDEX `ID_UNIQUE` ON `vacation_rental_db`.`users` (`id` ASC) ;

CREATE UNIQUE INDEX `email_UNIQUE` ON `vacation_rental_db`.`users` (`email` ASC) ;


-- -----------------------------------------------------
-- Table `vacation_rental_db`.`houses`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `vacation_rental_db`.`houses` ;

CREATE TABLE IF NOT EXISTS `vacation_rental_db`.`houses` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(64) NOT NULL,
  `description` LONGTEXT NOT NULL,
  `price` DECIMAL UNSIGNED NOT NULL,
  `max_person` SMALLINT UNSIGNED NOT NULL,
  `postal_code` VARCHAR(10) NOT NULL,
  `city` VARCHAR(45) NOT NULL,
  `street` VARCHAR(64) NOT NULL,
  `house_number` SMALLINT UNSIGNED NOT NULL,
  `square_meter` INT UNSIGNED NOT NULL,
  `room_count` SMALLINT UNSIGNED NOT NULL,
  `is_disabled` TINYINT NOT NULL DEFAULT 1,
  `owner_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_House_User1`
    FOREIGN KEY (`owner_id`)
    REFERENCES `vacation_rental_db`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE UNIQUE INDEX `ID_UNIQUE` ON `vacation_rental_db`.`houses` (`id` ASC) ;

CREATE INDEX `fk_House_User1_idx` ON `vacation_rental_db`.`houses` (`owner_id` ASC) ;


-- -----------------------------------------------------
-- Table `vacation_rental_db`.`tags`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `vacation_rental_db`.`tags` ;

CREATE TABLE IF NOT EXISTS `vacation_rental_db`.`tags` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `house_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_Tag_House1`
    FOREIGN KEY (`house_id`)
    REFERENCES `vacation_rental_db`.`houses` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE UNIQUE INDEX `ID_UNIQUE` ON `vacation_rental_db`.`tags` (`id` ASC) ;

CREATE INDEX `fk_Tag_House1_idx` ON `vacation_rental_db`.`tags` (`house_id` ASC) ;

CREATE UNIQUE INDEX `house_tag_constraint` ON `vacation_rental_db`.`tags` (`name` ASC, `house_id` ASC) ;


-- -----------------------------------------------------
-- Table `vacation_rental_db`.`typetables`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `vacation_rental_db`.`typetables` ;

CREATE TABLE IF NOT EXISTS `vacation_rental_db`.`typetables` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

CREATE UNIQUE INDEX `ID_UNIQUE` ON `vacation_rental_db`.`typetables` (`id` ASC) ;


-- -----------------------------------------------------
-- Table `vacation_rental_db`.`images`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `vacation_rental_db`.`images` ;

CREATE TABLE IF NOT EXISTS `vacation_rental_db`.`images` (
  `id` INT UNSIGNED NOT NULL,
  `uuID` VARCHAR(40) NOT NULL,
  `house_id` INT UNSIGNED NOT NULL,
  `typetable_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_Image_House1`
    FOREIGN KEY (`house_id`)
    REFERENCES `vacation_rental_db`.`houses` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Image_Typetable1`
    FOREIGN KEY (`typetable_id`)
    REFERENCES `vacation_rental_db`.`typetables` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE UNIQUE INDEX `ID_UNIQUE` ON `vacation_rental_db`.`images` (`id` ASC) ;

CREATE INDEX `fk_Image_House1_idx` ON `vacation_rental_db`.`images` (`house_id` ASC) ;

CREATE INDEX `fk_Image_Typetable1_idx` ON `vacation_rental_db`.`images` (`typetable_id` ASC) ;


-- -----------------------------------------------------
-- Table `vacation_rental_db`.`bookings`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `vacation_rental_db`.`bookings` ;

CREATE TABLE IF NOT EXISTS `vacation_rental_db`.`bookings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `is_confirmed` TINYINT NOT NULL DEFAULT 0,
  `booked_at` TIMESTAMP NULL,
  `user_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_Booking_User1`
    FOREIGN KEY (`user_id`)
    REFERENCES `vacation_rental_db`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE UNIQUE INDEX `ID_UNIQUE` ON `vacation_rental_db`.`bookings` (`id` ASC) ;

CREATE INDEX `fk_Booking_User1_idx` ON `vacation_rental_db`.`bookings` (`user_id` ASC) ;


-- -----------------------------------------------------
-- Table `vacation_rental_db`.`options`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `vacation_rental_db`.`options` ;

CREATE TABLE IF NOT EXISTS `vacation_rental_db`.`options` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `description` TEXT NOT NULL,
  `price` DECIMAL NOT NULL,
  `is_disabled` TINYINT NOT NULL DEFAULT 1,
  `house_id` INT UNSIGNED NOT NULL,
  `image_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_Option_House1`
    FOREIGN KEY (`house_id`)
    REFERENCES `vacation_rental_db`.`houses` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Option_Image1`
    FOREIGN KEY (`image_id`)
    REFERENCES `vacation_rental_db`.`images` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE UNIQUE INDEX `ID_UNIQUE` ON `vacation_rental_db`.`options` (`id` ASC) ;

CREATE INDEX `fk_Option_House1_idx` ON `vacation_rental_db`.`options` (`house_id` ASC) ;

CREATE INDEX `fk_Option_Image1_idx` ON `vacation_rental_db`.`options` (`image_id` ASC) ;


-- -----------------------------------------------------
-- Table `vacation_rental_db`.`bookingpositions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `vacation_rental_db`.`bookingpositions` ;

CREATE TABLE IF NOT EXISTS `vacation_rental_db`.`bookingpositions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `date_start` DATE NOT NULL,
  `date_end` DATE NOT NULL,
  `price_detail_list` TEXT NULL,
  `house_id` INT UNSIGNED NOT NULL,
  `booking_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_Bookingposition_House1`
    FOREIGN KEY (`house_id`)
    REFERENCES `vacation_rental_db`.`houses` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Bookingposition_Booking1`
    FOREIGN KEY (`booking_id`)
    REFERENCES `vacation_rental_db`.`bookings` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE UNIQUE INDEX `ID_UNIQUE` ON `vacation_rental_db`.`bookingpositions` (`id` ASC) ;

CREATE INDEX `fk_Bookingposition_House1_idx` ON `vacation_rental_db`.`bookingpositions` (`house_id` ASC) ;

CREATE INDEX `fk_Bookingposition_Booking1_idx` ON `vacation_rental_db`.`bookingpositions` (`booking_id` ASC) ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
