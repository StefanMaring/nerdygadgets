SET FOREIGN_KEY_CHECKS = 0;

USE nerdygadgets;

CREATE TABLE IF NOT EXISTS `nerdygadgets`.`customers_new` (
  `CustomerID` INT(11) NOT NULL AUTO_INCREMENT,
  `CustomerName` VARCHAR(100) NOT NULL,
  `AccountOpenedDate` DATE NULL,
  `EmailAddress` VARCHAR(100) NOT NULL,
  `IsPermittedToLogon` TINYINT(1) NOT NULL,
  `HashedPassword` LONGBLOB NULL,
  `PhoneNumber` VARCHAR(20) NOT NULL,
  `AddressLine` VARCHAR(60) NOT NULL,
  `AddressPostalCode` VARCHAR(10) NOT NULL,
  `AddressCity` VARCHAR(100) NOT NULL,
  `ValidFrom` DATETIME NOT NULL,
  `ValidTo` DATETIME NOT NULL,
  PRIMARY KEY (`CustomerID`),
  UNIQUE INDEX `EmailAddress_UNIQUE` (`EmailAddress` ))
ENGINE = InnoDB;

ALTER TABLE orders DROP CONSTRAINT IF EXISTS FK_Sales_Orders_CustomerID_Sales_Customers;

ALTER TABLE orders
ADD CONSTRAINT FK_Sales_Orders_CustomerID_Sales_Customers
FOREIGN KEY(CustomerID) REFERENCES customers_new(CustomerID)
ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE orders
ADD AddressLine VARCHAR(60) NOT NULL,
ADD AddressPostalCode VARCHAR(10) NOT NULL,
ADD AddressCity VARCHAR(100);

CREATE TABLE IF NOT EXISTS `reviews` (
  `CustomerID` int(11) NOT NULL,
  `KlantNaam` varchar(60) NOT NULL,
  `AantalSterren` int(5) NOT NULL,
  `Beschrijving` text NOT NULL,
  `Product` int(3) NOT NULL,
  PRIMARY KEY (`CustomerID`,`KlantNaam`,`Product`),
  CONSTRAINT `RelatieCustomer` FOREIGN KEY (`CustomerID`) REFERENCES `customers_new` (`CustomerID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `discountcode` 
(
 `discountcodeId` int(4) NOT NULL AUTO_INCREMENT, 
 `kortingscode_text` varchar(8) NOT NULL, 
 `usedCode` int(2) NOT NULL, 
 PRIMARY KEY (`discountcodeId`) ) 
 ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;

INSERT INTO coldroomtemperatures
values ('3654741', '5', '2022-12-21 08:46:27', '2.06', '2022-12-21 08:46:27' , '9999-12-31 23:59:59')

SET FOREIGN_KEY_CHECKS = 1;