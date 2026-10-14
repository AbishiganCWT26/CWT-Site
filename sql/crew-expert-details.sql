-- Additive migration for the Crew Management update (MariaDB/XAMPP).
ALTER TABLE crew
 ADD COLUMN IF NOT EXISTS educational_qualification TEXT NULL,
 ADD COLUMN IF NOT EXISTS domestic_experience VARCHAR(255) NULL,
 ADD COLUMN IF NOT EXISTS international_experience VARCHAR(255) NULL;
