-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema projeto
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema projeto
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `projeto` DEFAULT CHARACTER SET utf8 ;
USE `projeto` ;

ALTER SCHEMA `projeto`  DEFAULT CHARACTER SET utf8  DEFAULT COLLATE utf8_general_ci ;


-- -----------------------------------------------------
-- Table `projeto`.`sales`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `projeto`.`sales` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `transaction_id` INT(85) NULL UNIQUE,
  `store_name` VARCHAR(255) NULL,
  `date` DATETIME NULL,
  `revenue` DECIMAL(10,2) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `projeto`.`products`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `projeto`.`products` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `sales_id` INT NOT NULL,
  `name` VARCHAR(45) NULL,
  `price` DECIMAL(10,2) NULL,
  `date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_products_sales_idx` (`sales_id` ASC),
  CONSTRAINT `fk_products_sales`
    FOREIGN KEY (`sales_id`)
    REFERENCES `projeto`.`sales` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

