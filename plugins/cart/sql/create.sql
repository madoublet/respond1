CREATE TABLE IF NOT EXISTS Products (
  ProductId int(11) NOT NULL AUTO_INCREMENT,
  ProductUniqId varchar(50) NOT NULL,
  SKU varchar(50) DEFAULT NULL,
  `Name` varchar(255) NOT NULL,
  Quantity int(11) DEFAULT NULL,
  Price float(10,2) DEFAULT NULL,
  Priority int(11) DEFAULT NULL,
  PageUniqId varchar(50) NOT NULL,
  Created datetime NOT NULL,
  CreatedBy int(11) NOT NULL,
  LastModifiedBy int(11) NOT NULL,
  LastModifiedDate datetime NOT NULL,
  PRIMARY KEY (ProductId),
  KEY PageUniqId (PageUniqId)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE `Products`
  ADD CONSTRAINT Products_ibfk_1 FOREIGN KEY (PageUniqId) REFERENCES `Pages` (PageUniqId) ON DELETE CASCADE;