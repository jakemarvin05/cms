SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `tr_cms` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `tr_cms` ;

-- -----------------------------------------------------
-- Table `tr_cms`.`account`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tr_cms`.`account` ;

CREATE TABLE IF NOT EXISTS `tr_cms`.`account` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `login` VARCHAR(128) NOT NULL,
  `email` VARCHAR(128) NOT NULL,
  `first_name` VARCHAR(128) NOT NULL,
  `last_name` VARCHAR(128) NOT NULL,
  `creation_date` TIMESTAMP NOT NULL DEFAULT 0,
  `update_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_date` TIMESTAMP NULL,
  `password` CHAR(128) NOT NULL,
  `salt` CHAR(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `login_UNIQUE` (`login` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tr_cms`.`tag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tr_cms`.`tag` ;

CREATE TABLE IF NOT EXISTS `tr_cms`.`tag` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(128) NOT NULL,
  `creation_date` TIMESTAMP NOT NULL DEFAULT 0,
  `update_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_date` TIMESTAMP NULL,
  `account_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC),
  INDEX `fk_tag_account1_idx` (`account_id` ASC),
  CONSTRAINT `fk_tag_account1`
    FOREIGN KEY (`account_id`)
    REFERENCES `tr_cms`.`account` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tr_cms`.`category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tr_cms`.`category` ;

CREATE TABLE IF NOT EXISTS `tr_cms`.`category` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(128) NOT NULL,
  `creation_date` TIMESTAMP NOT NULL DEFAULT 0,
  `update_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_date` TIMESTAMP NULL,
  `account_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC),
  INDEX `fk_category_account1_idx` (`account_id` ASC),
  CONSTRAINT `fk_category_account1`
    FOREIGN KEY (`account_id`)
    REFERENCES `tr_cms`.`account` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tr_cms`.`template_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tr_cms`.`template_type` ;

CREATE TABLE IF NOT EXISTS `tr_cms`.`template_type` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tr_cms`.`template`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tr_cms`.`template` ;

CREATE TABLE IF NOT EXISTS `tr_cms`.`template` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(128) NOT NULL,
  `path` VARCHAR(255) NOT NULL,
  `template_type_id` INT NOT NULL,
  `creation_date` TIMESTAMP NOT NULL DEFAULT 0,
  `update_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_date` TIMESTAMP NULL,
  `account_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC),
  UNIQUE INDEX `path_UNIQUE` (`path` ASC),
  INDEX `fk_template_template_type_idx` (`template_type_id` ASC),
  INDEX `fk_template_account1_idx` (`account_id` ASC),
  CONSTRAINT `fk_template_template_type`
    FOREIGN KEY (`template_type_id`)
    REFERENCES `tr_cms`.`template_type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_template_account1`
    FOREIGN KEY (`account_id`)
    REFERENCES `tr_cms`.`account` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tr_cms`.`template_has_tag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tr_cms`.`template_has_tag` ;

CREATE TABLE IF NOT EXISTS `tr_cms`.`template_has_tag` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `tag_id` INT NOT NULL,
  `template_id` INT NOT NULL,
  `creation_date` TIMESTAMP NOT NULL DEFAULT 0,
  `update_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_date` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_template_has_tag_tag1_idx` (`tag_id` ASC),
  INDEX `fk_template_has_tag_template1_idx` (`template_id` ASC),
  CONSTRAINT `fk_template_has_tag_tag1`
    FOREIGN KEY (`tag_id`)
    REFERENCES `tr_cms`.`tag` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_template_has_tag_template1`
    FOREIGN KEY (`template_id`)
    REFERENCES `tr_cms`.`template` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tr_cms`.`template_has_category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tr_cms`.`template_has_category` ;

CREATE TABLE IF NOT EXISTS `tr_cms`.`template_has_category` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `category_id` INT NOT NULL,
  `template_id` INT NOT NULL,
  `creation_date` TIMESTAMP NOT NULL DEFAULT 0,
  `update_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_date` TIMESTAMP NULL,
  `account_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_template_has_category_category1_idx` (`category_id` ASC),
  INDEX `fk_template_has_category_template1_idx` (`template_id` ASC),
  INDEX `fk_template_has_category_account1_idx` (`account_id` ASC),
  CONSTRAINT `fk_template_has_category_category1`
    FOREIGN KEY (`category_id`)
    REFERENCES `tr_cms`.`category` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_template_has_category_template1`
    FOREIGN KEY (`template_id`)
    REFERENCES `tr_cms`.`template` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_template_has_category_account1`
    FOREIGN KEY (`account_id`)
    REFERENCES `tr_cms`.`account` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tr_cms`.`group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tr_cms`.`group` ;

CREATE TABLE IF NOT EXISTS `tr_cms`.`group` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(128) NOT NULL,
  `creation_date` TIMESTAMP NOT NULL DEFAULT 0,
  `update_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_date` TIMESTAMP NULL,
  `account_id` INT NOT NULL,
  `footer_id` INT NOT NULL,
  `header_id` INT NOT NULL,
  `scripts` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_group_account1_idx` (`account_id` ASC),
  INDEX `fk_group_template1_idx` (`footer_id` ASC),
  INDEX `fk_group_template2_idx` (`header_id` ASC),
  CONSTRAINT `fk_group_account1`
    FOREIGN KEY (`account_id`)
    REFERENCES `tr_cms`.`account` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_group_template1`
    FOREIGN KEY (`footer_id`)
    REFERENCES `tr_cms`.`template` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_group_template2`
    FOREIGN KEY (`header_id`)
    REFERENCES `tr_cms`.`template` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tr_cms`.`page`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tr_cms`.`page` ;

CREATE TABLE IF NOT EXISTS `tr_cms`.`page` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(128) NOT NULL,
  `uri` VARCHAR(255) NOT NULL,
  `creation_date` TIMESTAMP NOT NULL DEFAULT 0,
  `update_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_date` TIMESTAMP NULL,
  `account_id` INT NOT NULL,
  `scripts` TEXT NULL,
  `group_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC),
  UNIQUE INDEX `address_UNIQUE` (`uri` ASC),
  INDEX `fk_page_account1_idx` (`account_id` ASC),
  INDEX `fk_page_group1_idx` (`group_id` ASC),
  CONSTRAINT `fk_page_account1`
    FOREIGN KEY (`account_id`)
    REFERENCES `tr_cms`.`account` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_page_group1`
    FOREIGN KEY (`group_id`)
    REFERENCES `tr_cms`.`group` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tr_cms`.`page_has_template`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tr_cms`.`page_has_template` ;

CREATE TABLE IF NOT EXISTS `tr_cms`.`page_has_template` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `template_id` INT NOT NULL,
  `page_id` INT NOT NULL,
  `position` INT NOT NULL,
  `creation_date` TIMESTAMP NOT NULL DEFAULT 0,
  `update_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_date` TIMESTAMP NULL,
  `account_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_page_has_template_template1_idx` (`template_id` ASC),
  INDEX `fk_page_has_template_page1_idx` (`page_id` ASC),
  INDEX `fk_page_has_template_account1_idx` (`account_id` ASC),
  CONSTRAINT `fk_page_has_template_template1`
    FOREIGN KEY (`template_id`)
    REFERENCES `tr_cms`.`template` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_page_has_template_page1`
    FOREIGN KEY (`page_id`)
    REFERENCES `tr_cms`.`page` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_page_has_template_account1`
    FOREIGN KEY (`account_id`)
    REFERENCES `tr_cms`.`account` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tr_cms`.`role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tr_cms`.`role` ;

CREATE TABLE IF NOT EXISTS `tr_cms`.`role` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tr_cms`.`account_has_role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tr_cms`.`account_has_role` ;

CREATE TABLE IF NOT EXISTS `tr_cms`.`account_has_role` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `account_id` INT NOT NULL,
  `role_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_account_has_role_account1_idx` (`account_id` ASC),
  INDEX `fk_account_has_role_role1_idx` (`role_id` ASC),
  CONSTRAINT `fk_account_has_role_account1`
    FOREIGN KEY (`account_id`)
    REFERENCES `tr_cms`.`account` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_account_has_role_role1`
    FOREIGN KEY (`role_id`)
    REFERENCES `tr_cms`.`role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tr_cms`.`image`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tr_cms`.`image` ;

CREATE TABLE IF NOT EXISTS `tr_cms`.`image` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(128) NOT NULL,
  `path` VARCHAR(255) NOT NULL,
  `creation_date` TIMESTAMP NOT NULL DEFAULT 0,
  `update_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_date` TIMESTAMP NULL,
  `account_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_image_account1_idx` (`account_id` ASC),
  CONSTRAINT `fk_image_account1`
    FOREIGN KEY (`account_id`)
    REFERENCES `tr_cms`.`account` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tr_cms`.`page_meta`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tr_cms`.`page_meta` ;

CREATE TABLE IF NOT EXISTS `tr_cms`.`page_meta` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` TEXT NOT NULL,
  `meta_author` VARCHAR(128) NULL,
  `meta_description` TEXT NULL,
  `meta_keywords` TEXT NULL,
  `og_type` VARCHAR(128) NULL,
  `og_url` VARCHAR(128) NULL,
  `og_image_path` VARCHAR(256) NULL,
  `page_id` INT NOT NULL,
  `favicon_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_page_meta_page1_idx` (`page_id` ASC),
  INDEX `fk_page_meta_image1_idx` (`favicon_id` ASC),
  CONSTRAINT `fk_page_meta_page1`
    FOREIGN KEY (`page_id`)
    REFERENCES `tr_cms`.`page` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_page_meta_image1`
    FOREIGN KEY (`favicon_id`)
    REFERENCES `tr_cms`.`image` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tr_cms`.`log_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tr_cms`.`log_type` ;

CREATE TABLE IF NOT EXISTS `tr_cms`.`log_type` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(128) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tr_cms`.`log`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tr_cms`.`log` ;

CREATE TABLE IF NOT EXISTS `tr_cms`.`log` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `message` TEXT NOT NULL,
  `creation_date` TIMESTAMP NOT NULL DEFAULT 0,
  `log_type_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_log_log_type1_idx` (`log_type_id` ASC),
  CONSTRAINT `fk_log_log_type1`
    FOREIGN KEY (`log_type_id`)
    REFERENCES `tr_cms`.`log_type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tr_cms`.`hook_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tr_cms`.`hook_type` ;

CREATE TABLE IF NOT EXISTS `tr_cms`.`hook_type` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(128) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tr_cms`.`hook`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tr_cms`.`hook` ;

CREATE TABLE IF NOT EXISTS `tr_cms`.`hook` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(128) NOT NULL,
  `template_id` INT NOT NULL,
  `hook_type_id` INT NOT NULL,
  `default_boolean_value` TINYINT(1) NULL,
  `default_text_value` TEXT NULL,
  `creation_date` TIMESTAMP NOT NULL DEFAULT 0,
  `update_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_date` TIMESTAMP NULL,
  `account_id` INT NOT NULL,
  `default_image_value_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_hook_template1_idx` (`template_id` ASC),
  INDEX `fk_hook_hook_type1_idx` (`hook_type_id` ASC),
  INDEX `fk_hook_account1_idx` (`account_id` ASC),
  INDEX `fk_hook_image1_idx` (`default_image_value_id` ASC),
  CONSTRAINT `fk_hook_template1`
    FOREIGN KEY (`template_id`)
    REFERENCES `tr_cms`.`template` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_hook_hook_type1`
    FOREIGN KEY (`hook_type_id`)
    REFERENCES `tr_cms`.`hook_type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_hook_account1`
    FOREIGN KEY (`account_id`)
    REFERENCES `tr_cms`.`account` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_hook_image1`
    FOREIGN KEY (`default_image_value_id`)
    REFERENCES `tr_cms`.`image` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tr_cms`.`hook_value`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tr_cms`.`hook_value` ;

CREATE TABLE IF NOT EXISTS `tr_cms`.`hook_value` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `boolean_value` TINYINT(1) NULL,
  `text_value` TEXT NULL,
  `hook_id` INT NOT NULL,
  `page_id` INT NOT NULL,
  `creation_date` TIMESTAMP NOT NULL DEFAULT 0,
  `update_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_date` TIMESTAMP NULL,
  `account_id` INT NOT NULL,
  `image_value_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_hook_value_hook1_idx` (`hook_id` ASC),
  INDEX `fk_hook_value_page1_idx` (`page_id` ASC),
  INDEX `fk_hook_value_account1_idx` (`account_id` ASC),
  INDEX `fk_hook_value_image1_idx` (`image_value_id` ASC),
  CONSTRAINT `fk_hook_value_hook1`
    FOREIGN KEY (`hook_id`)
    REFERENCES `tr_cms`.`hook` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_hook_value_page1`
    FOREIGN KEY (`page_id`)
    REFERENCES `tr_cms`.`page` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_hook_value_account1`
    FOREIGN KEY (`account_id`)
    REFERENCES `tr_cms`.`account` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_hook_value_image1`
    FOREIGN KEY (`image_value_id`)
    REFERENCES `tr_cms`.`image` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tr_cms`.`config`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tr_cms`.`config` ;

CREATE TABLE IF NOT EXISTS `tr_cms`.`config` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(128) NOT NULL,
  `value` VARCHAR(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC),
  UNIQUE INDEX `value_UNIQUE` (`value` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tr_cms`.`redirect`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tr_cms`.`redirect` ;

CREATE TABLE IF NOT EXISTS `tr_cms`.`redirect` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `url` VARCHAR(255) NOT NULL,
  `page_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `url_UNIQUE` (`url` ASC),
  INDEX `fk_redirect_page1_idx` (`page_id` ASC),
  CONSTRAINT `fk_redirect_page1`
    FOREIGN KEY (`page_id`)
    REFERENCES `tr_cms`.`page` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
