<?php /*
-- --------------------------------------------------------------------------
CREATE TABLE `darintyl_database`.`docs_documents`(
  `int_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `str_username` VARCHAR(70) NOT NULL,
  `int_access_count` INTEGER UNSIGNED,
  `str_title` VARCHAR(255) NOT NULL,
  `str_description` VARCHAR(255),
  `str_keywords` VARCHAR(255),
  `str_path` VARCHAR(255) NOT NULL,
  `dat_uploaded` DATETIME NOT NULL,
  `dat_last_updated` DATETIME NOT NULL,
  PRIMARY KEY (`int_id`)
)
ENGINE = InnoDB;
-- --------------------------------------------------------------------------
CREATE TABLE `darintyl_database`.`docs_document_category_rels`(
  `int_document_id` INTEGER UNSIGNED NOT NULL,
  `int_category_id` INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY (`int_document_id`,`int_category_id`)
)
ENGINE = InnoDB;
-- --------------------------------------------------------------------------
CREATE TABLE `darintyl_database`.`docs_categories`(
  `int_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `str_category` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`int_id`)
)
ENGINE = InnoDB;
-- --------------------------------------------------------------------------
-- --------------------------------------------------------------------------
SET autocommit=0;
START TRANSACTION;
-- --------------------------------------------------------------------------
INSERT INTO `darintyl_database`.`docs_categories`(
  `str_category`
) 
VALUES
  ('Form'),
  ('Report'), 
  ('Brochure'), 
  ('Newsletter'), 
  ('Tutorial'), 
  ('Other')
;
-- --------------------------------------------------------------------------
COMMIT;
-- --------------------------------------------------------------------------
*/ ?>