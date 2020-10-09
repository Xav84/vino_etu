-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema e1995672
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema e1995672
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `e1995094` DEFAULT CHARACTER SET utf8mb4 ;
USE `e1995094` ;

-- -----------------------------------------------------
-- Table `e1995672`.`vino__type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `e1995094`.`vino__type` (
  `id` INT(11) NOT NULL,
  `type` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `e1995672`.`vino__bouteille`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `e1995094`.`vino__bouteille` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(200) NULL,
  `image` VARCHAR(200) NULL,
  `code_saq` VARCHAR(50) NULL,
  `pays` VARCHAR(50) NULL,
  `description` VARCHAR(200) NULL,
  `prix_saq` DECIMAL(10,2) NULL,
  `url_saq` VARCHAR(200) NULL,
  `url_img` VARCHAR(200) NULL,
  `format` VARCHAR(20) NULL,
  `fk_type_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_vino__bouteille_vino__type_idx` (`fk_type_id` ASC),
  CONSTRAINT `fk_vino__bouteille_vino__type`
    FOREIGN KEY (`fk_type_id`)
    REFERENCES `e1995094`.`vino__type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 11
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `e1995672`.`vino__utilisateur`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `e1995094`.`vino__utilisateur` (
  `id_utilisateur` INT NOT NULL AUTO_INCREMENT,
  `prenom_utilisateur` VARCHAR(200) NOT NULL,
  `nom_utilisateur` VARCHAR(200) NOT NULL,
  `password_utilisateur` VARCHAR(200) NOT NULL,
  `courriel_utilisateur` VARCHAR(200) NOT NULL,
  `type_utilisateur` INT NOT NULL,
  PRIMARY KEY (`id_utilisateur`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `e1995672`.`vino__cellier`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `e1995094`.`vino__cellier` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `date_creation_cellier` DATE NOT NULL,
  `notes_cellier` VARCHAR(200) NULL,
  `fk_id_utilisateur` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_vino__cellier_vino__utilisateur1_idx` (`fk_id_utilisateur` ASC),
  CONSTRAINT `fk_vino__cellier_vino__utilisateur1`
    FOREIGN KEY (`fk_id_utilisateur`)
    REFERENCES `e1995094`.`vino__utilisateur` (`id_utilisateur`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 10
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `e1995672`.`cellier__bouteille`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `e1995094`.`cellier__bouteille` (
  `vino__cellier_id` INT(11) NOT NULL,
  `vino__bouteille_id` INT(11) NOT NULL,
  `date_achat` DATE NOT NULL,
  `garde_jusqua` VARCHAR(45) NULL,
  `notes` VARCHAR(200) NULL,
  `prix` DECIMAL(10,2) NULL,
  `quantite` INT(11) NOT NULL,
  `millesime` INT(11) NULL,
    `note_degustation` INT(11) NULL,
  PRIMARY KEY (`vino__cellier_id`, `vino__bouteille_id`),
  INDEX `fk_vino__cellier_has_vino__bouteille_vino__bouteille1_idx` (`vino__bouteille_id` ASC),
  INDEX `fk_vino__cellier_has_vino__bouteille_vino__cellier1_idx` (`vino__cellier_id` ASC),
  CONSTRAINT `fk_vino__cellier_has_vino__bouteille_vino__cellier1`
    FOREIGN KEY (`vino__cellier_id`)
    REFERENCES `e1995094`.`vino__cellier` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_vino__cellier_has_vino__bouteille_vino__bouteille1`
    FOREIGN KEY (`vino__bouteille_id`)
    REFERENCES `e1995094`.`vino__bouteille` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

--
-- Contenu de la table `vino__type`
--

INSERT INTO `e1995094`.`vino__type` VALUES(1, 'Vin rouge');
INSERT INTO `e1995094`.`vino__type` VALUES(2, 'Vin blanc');

--
-- Contenu de la table `vino__bouteille`
--

INSERT INTO `e1995094`.`vino__bouteille` VALUES(1, 'Borsao Seleccion', '//s7d9.scene7.com/is/image/SAQ/10324623_is?$saq-rech-prod-gril$', '10324623', 'Espagne', 'Vin rouge\r\n         \r\n      \r\n      \r\n      Espagne, 750 ml\r\n      \r\n      \r\n      Code SAQ : 10324623', 11, 'https://www.saq.com/page/fr/saqcom/vin-rouge/borsao-seleccion/10324623', '//s7d9.scene7.com/is/image/SAQ/10324623_is?$saq-rech-prod-gril$', ' 750 ml',1);
INSERT INTO `e1995094`.`vino__bouteille` VALUES(2, 'Monasterio de Las Vinas Gran Reserva', '//s7d9.scene7.com/is/image/SAQ/10359156_is?$saq-rech-prod-gril$', '10359156', 'Espagne', 'Vin rouge\r\n         \r\n      \r\n      \r\n      Espagne, 750 ml\r\n      \r\n      \r\n      Code SAQ : 10359156', 19, 'https://www.saq.com/page/fr/saqcom/vin-rouge/monasterio-de-las-vinas-gran-reserva/10359156', '//s7d9.scene7.com/is/image/SAQ/10359156_is?$saq-rech-prod-gril$', ' 750 ml', 1);
INSERT INTO `e1995094`.`vino__bouteille` VALUES(3, 'Castano Hecula', '//s7d9.scene7.com/is/image/SAQ/11676671_is?$saq-rech-prod-gril$', '11676671', 'Espagne', 'Vin rouge\r\n         \r\n      \r\n      \r\n      Espagne, 750 ml\r\n      \r\n      \r\n      Code SAQ : 11676671', 12, 'https://www.saq.com/page/fr/saqcom/vin-rouge/castano-hecula/11676671', '//s7d9.scene7.com/is/image/SAQ/11676671_is?$saq-rech-prod-gril$', ' 750 ml', 1);
INSERT INTO `e1995094`.`vino__bouteille` VALUES(4, 'Campo Viejo Tempranillo Rioja', '//s7d9.scene7.com/is/image/SAQ/11462446_is?$saq-rech-prod-gril$', '11462446', 'Espagne', 'Vin rouge\r\n         \r\n      \r\n      \r\n      Espagne, 750 ml\r\n      \r\n      \r\n      Code SAQ : 11462446', 14, 'https://www.saq.com/page/fr/saqcom/vin-rouge/campo-viejo-tempranillo-rioja/11462446', '//s7d9.scene7.com/is/image/SAQ/11462446_is?$saq-rech-prod-gril$', ' 750 ml', 1);
INSERT INTO `e1995094`.`vino__bouteille` VALUES(5, 'Bodegas Atalaya Laya 2017', '//s7d9.scene7.com/is/image/SAQ/12375942_is?$saq-rech-prod-gril$', '12375942', 'Espagne', 'Vin rouge\r\n         \r\n      \r\n      \r\n      Espagne, 750 ml\r\n      \r\n      \r\n      Code SAQ : 12375942', 17, 'https://www.saq.com/page/fr/saqcom/vin-rouge/bodegas-atalaya-laya-2017/12375942', '//s7d9.scene7.com/is/image/SAQ/12375942_is?$saq-rech-prod-gril$', ' 750 ml', 1);
INSERT INTO `e1995094`.`vino__bouteille` VALUES(6, 'Vin Vault Pinot Grigio', '//s7d9.scene7.com/is/image/SAQ/13467048_is?$saq-rech-prod-gril$', '13467048', 'États-Unis', 'Vin blanc\r\n         \r\n      \r\n      \r\n      États-Unis, 3 L\r\n      \r\n      \r\n      Code SAQ : 13467048', NULL, 'https://www.saq.com/page/fr/saqcom/vin-blanc/vin-vault-pinot-grigio/13467048', '//s7d9.scene7.com/is/image/SAQ/13467048_is?$saq-rech-prod-gril$', ' 3 L', 2);
INSERT INTO `e1995094`.`vino__bouteille` VALUES(7, 'Huber Riesling Engelsberg 2017', '//s7d9.scene7.com/is/image/SAQ/13675841_is?$saq-rech-prod-gril$', '13675841', 'Autriche', 'Vin blanc\r\n         \r\n      \r\n      \r\n      Autriche, 750 ml\r\n      \r\n      \r\n      Code SAQ : 13675841', 22, 'https://www.saq.com/page/fr/saqcom/vin-blanc/huber-riesling-engelsberg-2017/13675841', '//s7d9.scene7.com/is/image/SAQ/13675841_is?$saq-rech-prod-gril$', ' 750 ml', 2);
INSERT INTO `e1995094`.`vino__bouteille` VALUES(8, 'Dominio de Tares Estay Castilla y Léon 2015', '//s7d9.scene7.com/is/image/SAQ/13802571_is?$saq-rech-prod-gril$', '13802571', 'Espagne', 'Vin rouge\r\n         \r\n      \r\n      \r\n      Espagne, 750 ml\r\n      \r\n      \r\n      Code SAQ : 13802571', 18, 'https://www.saq.com/page/fr/saqcom/vin-rouge/dominio-de-tares-estay-castilla-y-leon-2015/13802571', '//s7d9.scene7.com/is/image/SAQ/13802571_is?$saq-rech-prod-gril$', ' 750 ml', 1);
INSERT INTO `e1995094`.`vino__bouteille` VALUES(9, 'Tessellae Old Vines Côtes du Roussillon 2016', '//s7d9.scene7.com/is/image/SAQ/12216562_is?$saq-rech-prod-gril$', '12216562', 'France', 'Vin rouge\r\n         \r\n      \r\n      \r\n      France, 750 ml\r\n      \r\n      \r\n      Code SAQ : 12216562', 21, 'https://www.saq.com/page/fr/saqcom/vin-rouge/tessellae-old-vines-cotes-du-roussillon-2016/12216562', '//s7d9.scene7.com/is/image/SAQ/12216562_is?$saq-rech-prod-gril$', ' 750 ml', 1);
INSERT INTO `e1995094`.`vino__bouteille` VALUES(10, 'Tenuta Il Falchetto Bricco Paradiso -... 2015', '//s7d9.scene7.com/is/image/SAQ/13637422_is?$saq-rech-prod-gril$', '13637422', 'Italie', 'Vin rouge\r\n         \r\n      \r\n      \r\n      Italie, 750 ml\r\n      \r\n      \r\n      Code SAQ : 13637422', 34, 'https://www.saq.com/page/fr/saqcom/vin-rouge/tenuta-il-falchetto-bricco-paradiso---barbera-dasti-superiore-docg-2015/13637422', '//s7d9.scene7.com/is/image/SAQ/13637422_is?$saq-rech-prod-gril$', ' 750 ml', 1);

-- --------------------------------------------------------
--
-- Contenu de la table `vino__utilisateur`
--

-- --------------------------------------------------------

INSERT INTO `e1995094`.`vino__utilisateur` VALUES(1, 'admin', 'admin', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 'admin@hotmail.com',1);
INSERT INTO `e1995094`.`vino__utilisateur` VALUES(2, 'Dion', 'Céline', 'ed26b0c2aa483333cf9d07d76acd90e212c6aad624c404ee20e533443ed4c808', 'celine_dion@hotmail.com',2);
INSERT INTO `e1995094`.`vino__utilisateur` VALUES(3, 'Cage', 'Nicolas', 'a126b42b062591c2dded4022bd551a241df7fccb8f078ee3b143613d8bb20c53', 'nicolas_cage@hotmail.com',2);


--
-- Contenu de la table `vino__cellier`
--

INSERT INTO `e1995094`.`vino__cellier` VALUES(1, '2019-01-16', 'Contient les meilleures bouteilles au monde',2);
INSERT INTO `e1995094`.`vino__cellier` VALUES(2, '2020-09-07', 'Vin cheap',3);

--
-- Contenu de la table `cellier_bouteille`
--

INSERT INTO `e1995094`.`cellier__bouteille` VALUES(1, 5, '2019-01-16', '2020', 'excellent vin', 22, 10, 1999,0);
INSERT INTO `e1995094`.`cellier__bouteille` VALUES(1, 4, '2020-09-07', '2015', '', 0, 10, 2000,0);
INSERT INTO `e1995094`.`cellier__bouteille` VALUES(2, 8, '2019-01-26', 'non', '', 23.52, 12, 2015,0);
INSERT INTO `e1995094`.`cellier__bouteille` VALUES(2, 6, '2019-01-26', 'non', '', 23.52, 8, 2015,0);
INSERT INTO `e1995094`.`cellier__bouteille` VALUES(2, 3, '2020-09-07', '2050', '', 0, 1, 0,0);
INSERT INTO `e1995094`.`cellier__bouteille` VALUES(2, 2, '2020-09-07', 'non', '', 0, 15, 0,0);
INSERT INTO `e1995094`.`cellier__bouteille` VALUES(2, 1, '2020-09-07', '2150', '', 0, 3, 0,0);
INSERT INTO `e1995094`.`cellier__bouteille` VALUES(2, 7, '2020-09-07', 'non', '', 0, 2, 0,0);

-- --------------------------------------------------------


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
