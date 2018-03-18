-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema osoitekirja
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema osoitekirja
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `osoitekirja` DEFAULT CHARACTER SET utf8 ;
USE `osoitekirja` ;

-- -----------------------------------------------------
-- Table `osoitekirja`.`Location`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `osoitekirja`.`Location` (
  `location_id` INT NOT NULL AUTO_INCREMENT,  
  `street_address` VARCHAR(45) NULL,
  `street_number` VARCHAR(45) NULL,
  `city` VARCHAR(30) NULL,
  `zip` VARCHAR(30) NULL,
  `country` VARCHAR(30) NULL,
  `latitude` VARCHAR(30) NULL,
  `longitude` VARCHAR(30) NULL,  
  
  PRIMARY KEY (`Location_id`),
  INDEX `ZipIndex` (`Zip` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `osoitekirja`.`Event`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `osoitekirja`.`Person` (
  `Person_id` INT NOT NULL AUTO_INCREMENT,
  `First_name` VARCHAR(45) NULL COMMENT 'First name of an person',
  `Last_name` VARCHAR(45) NULL COMMENT 'Last name of an person', 
  `Location_Location_id` INT NOT NULL,
  PRIMARY KEY (`Person_id`),
  INDEX `fk_Event_Location1_idx` (`Location_Location_id` ASC),
  CONSTRAINT `fk_Event_Location1`
    FOREIGN KEY (`Location_Location_id`)
    REFERENCES `osoitekirja`.`Location` (`Location_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

