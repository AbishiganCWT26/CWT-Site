-- Link a project image to one of the logos managed in Products.
ALTER TABLE projects ADD COLUMN IF NOT EXISTS logo_product_id int(11) DEFAULT NULL AFTER image_path;
ALTER TABLE projects ADD CONSTRAINT projects_logo_product_fk FOREIGN KEY (logo_product_id) REFERENCES products (id) ON DELETE SET NULL ON UPDATE CASCADE;