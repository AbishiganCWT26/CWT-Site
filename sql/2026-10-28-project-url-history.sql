-- Preserve public project URLs when project names change.
CREATE TABLE IF NOT EXISTS project_slug_history (
    slug varchar(255) NOT NULL,
    project_id int(11) NOT NULL,
    PRIMARY KEY (slug),
    KEY project_slug_history_project_id (project_id),
    CONSTRAINT project_slug_history_project_fk FOREIGN KEY (project_id)
        REFERENCES projects (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
INSERT IGNORE INTO project_slug_history (slug, project_id) SELECT slug, id FROM projects;
-- Restore the two known earlier links for this existing project.
INSERT IGNORE INTO project_slug_history (slug, project_id)
    SELECT 'Data-Analytics-Platform', id FROM projects WHERE id = 3;
INSERT IGNORE INTO project_slug_history (slug, project_id)
    SELECT 'Data-Analytics-Tool-DAT', id FROM projects WHERE id = 3;