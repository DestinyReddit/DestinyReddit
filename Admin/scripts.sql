DROP TABLE VendorArmor;

CREATE TABLE VendorArmor(
   VendorArmorId BIGSERIAL Primary Key,
   VendorName TEXT,
   ArmorName TEXT,
   ArmorType TEXT,
   Perks1 TEXT,
   Perks2 TEXT,
   Perks3 TEXT,
   Intelligence TEXT,
   Discipline TEXT,
   Strength TEXT,
   RollPercent TEXT,
   T12 TEXT
);

SELECT * FROM VendorArmor
