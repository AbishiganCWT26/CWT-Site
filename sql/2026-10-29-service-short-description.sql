-- Required by the service editor and homepage service cards.
ALTER TABLE services ADD COLUMN IF NOT EXISTS short_description varchar(255) DEFAULT NULL AFTER description;