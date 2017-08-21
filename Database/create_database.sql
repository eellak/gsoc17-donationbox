# First of all for security reasons, if the database exists, delete it.
DROP DATABASE IF EXISTS donationbox_network;


# Creating the database.
CREATE DATABASE donationbox_network CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


# Creating the tables and their relationships.
CREATE TABLE IF NOT EXISTS donationbox_network.Box (
  idBox INT(11) NOT NULL,
  idLocation INT(11) NOT NULL,
  idBoxStatus INT(11) NOT NULL,
  DateInstalled DATETIME NULL DEFAULT NULL,
  DateTimeLastSeen DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  DateUpdated DATETIME NULL DEFAULT NULL,
  SWVersion TEXT CHARACTER SET utf8 NOT NULL,
  HWVersion TEXT CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (idBox),
  INDEX fk_Box_BoxStatus1_idx (idBoxStatus ASC),
  INDEX fk_Box_Location1_idx (idLocation ASC),
  CONSTRAINT fk_Box_BoxStatus1
    FOREIGN KEY (idBoxStatus)
    REFERENCES donationbox_network.BoxStatus (idBoxStatus)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_Box_Location1
    FOREIGN KEY (idLocation)
    REFERENCES donationbox_network.Location (idLocation)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin


CREATE TABLE IF NOT EXISTS donationbox_network.donation (
  iddonation INT(11) NOT NULL AUTO_INCREMENT,
  idbox INT(11) NOT NULL,
  DonationTime DATETIME NULL DEFAULT NULL,
  Amount FLOAT(11) NOT NULL DEFAULT 0,
  idproject INT(11) NOT NULL,
  DBTime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UID VARCHAR(10) CHARACTER SET utf8 NULL DEFAULT NULL,
  email VARCHAR(100) CHARACTER SET utf8 NULL DEFAULT NULL,
  PRIMARY KEY (iddonation),
  INDEX fk_donation_Box1_idx (idbox ASC),
  INDEX fk_donation_Project1_idx (idproject ASC),
  CONSTRAINT fk_donation_Box1
    FOREIGN KEY (idbox)
    REFERENCES donationbox_network.Box (idBox)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_donation_Project1
    FOREIGN KEY (idproject)
    REFERENCES donationbox_network.Project (idProject)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 12
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin


CREATE TABLE IF NOT EXISTS donationbox_network.BoxStatus (
  idBoxStatus INT(11) NOT NULL,
  BoxStatus VARCHAR(45) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (idBoxStatus))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin


CREATE TABLE IF NOT EXISTS donationbox_network.ProjectStatus (
  idProjectStatus INT(11) NOT NULL,
  Status VARCHAR(45) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (idProjectStatus))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin


CREATE TABLE IF NOT EXISTS donationbox_network.Project (
  idProject INT(11) NOT NULL,
  Goal INT(11) NULL DEFAULT NULL,
  Title VARCHAR(255) CHARACTER SET utf8 NULL DEFAULT NULL,
  ShortDescription VARCHAR(1024) CHARACTER SET utf8 NULL DEFAULT NULL,
  Description MEDIUMTEXT CHARACTER SET utf8 NULL DEFAULT NULL,
  Video VARCHAR(255) CHARACTER SET utf8 NULL DEFAULT NULL,
  FeaturedImage VARCHAR(255) CHARACTER SET utf8 NULL DEFAULT NULL,
  idOrganization INT NOT NULL,
  DateFinish DATETIME NULL DEFAULT NULL,
  idProjectStatus INT NOT NULL,
  PRIMARY KEY (idProject),
  INDEX fk_Project_ProjectStatus_idx (idProjectStatus ASC),
  INDEX fk_Project_Organization1_idx (idOrganization ASC),
  CONSTRAINT fk_Project_ProjectStatus
    FOREIGN KEY (idProjectStatus)
    REFERENCES donationbox_network.ProjectStatus (idProjectStatus)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_Project_Organization1
    FOREIGN KEY (idOrganization)
    REFERENCES donationbox_network.Organization (idOrganization)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin


CREATE TABLE IF NOT EXISTS donationbox_network.Organization (
  idOrganization INT(11) NOT NULL,
  Title VARCHAR(255) CHARACTER SET utf8 NOT NULL,
  Description TEXT CHARACTER SET utf8 NULL DEFAULT NULL,
  Email VARCHAR(255) CHARACTER SET utf8 NOT NULL,
  PhoneNumber VARCHAR(45) CHARACTER SET utf8 NOT NULL,
  VATNumber VARCHAR(45) CHARACTER SET utf8 NULL DEFAULT NULL,
  LogoURL VARCHAR(255) CHARACTER SET utf8 NULL DEFAULT NULL,
  PRIMARY KEY (idOrganization))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin


CREATE TABLE IF NOT EXISTS donationbox_network.ProjectBox (
  idPlatformProject INT(11) NOT NULL,
  idBox INT(11) NOT NULL,
  idLocalProject INT(11) NULL,
  PRIMARY KEY (idBox, idPlatformProject),
  INDEX fk_ProjectBox_Project1_idx (idPlatformProject ASC),
  CONSTRAINT fk_ProjectBox_Box1
    FOREIGN KEY (idBox)
    REFERENCES donationbox_network.Box (idBox)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_ProjectBox_Project1
    FOREIGN KEY (idPlatformProject)
    REFERENCES donationbox_network.Project (idProject)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin


CREATE TABLE IF NOT EXISTS donationbox_network.Location (
  idLocation INT(11) NOT NULL,
  Location GEOMETRY NOT NULL,
  Name VARCHAR(45) CHARACTER SET utf8 NOT NULL,
  Address VARCHAR(100) CHARACTER SET utf8 NOT NULL,
  City VARCHAR(45) CHARACTER SET utf8 NOT NULL,
  Country VARCHAR(45) CHARACTER SET utf8 NOT NULL,
  State VARCHAR(45) CHARACTER SET utf8 NULL DEFAULT NULL,
  PhoneNumber VARCHAR(45) NULL,
  PRIMARY KEY (idLocation))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin
