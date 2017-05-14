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

CREATE TABLE VendorWeapon(
   VendorWeaponId BIGSERIAL Primary Key,
   VendorName TEXT,
   WeaponName TEXT,
   WeaponType TEXT,
   Perks1 TEXT,
   Perks2 TEXT,
   Perks3 TEXT,
   Perks4 TEXT,
   Perks5 TEXT,   
);