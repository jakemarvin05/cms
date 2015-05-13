alter table `page_meta` modify `title` text;
alter table `page_meta` modify `favicon_id` int;

CREATE TABLE IF NOT EXISTS `tr_cms`.`group_meta` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` TEXT NOT NULL,
  `meta_author` VARCHAR(128) NULL,
  `meta_description` TEXT NULL,
  `meta_keywords` TEXT NULL,
  `og_type` VARCHAR(128) NULL,
  `og_url` VARCHAR(218) NULL,
  `og_image_id` INT NOT NULL,
  `group_id` INT NOT NULL,
  `favicon_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_group_meta_group1_idx` (`group_id` ASC),
  INDEX `fk_group_meta_image1_idx` (`favicon_id` ASC),
  INDEX `fk_group_meta_image2_idx` (`og_image_id` ASC),
  CONSTRAINT `fk_group_meta_group1`
    FOREIGN KEY (`group_id`)
    REFERENCES `tr_cms`.`group` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_group_meta_image1`
    FOREIGN KEY (`favicon_id`)
    REFERENCES `tr_cms`.`image` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_group_meta_image2`
    FOREIGN KEY (`og_image_id`)
    REFERENCES `tr_cms`.`image` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

alter table `log` add `account_id` int;
alter table `log` add constraint `fk_log_account1_idx` foreign key (account_id) references account(id);
alter table `log` add `item_id` int;

alter table `log_type` add `reference` varchar(128);

delete from `log_type`;
insert into log_type (code, reference) values ('LOGIN', 'account');
insert into log_type (code, reference) values ('LOGOUT', 'account');
insert into log_type (code, reference) values ('LOGIN_ATTEMPT', 'account');

