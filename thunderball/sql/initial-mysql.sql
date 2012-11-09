-- Thunderball Project Payment
-- initial script for mysql databases
-- creates a default user "admin@thunderball" with password "admin"



INSERT INTO project_category (name) VALUES ('A');
INSERT INTO project_category (name) VALUES ('B');
INSERT INTO project_category (name) VALUES ('C');


INSERT INTO project_role (name) VALUES ('Projektleiter');
INSERT INTO project_role (name) VALUES ('Entwickler');

INSERT INTO project_status (name) VALUES ('Warten');
INSERT INTO project_status (name) VALUES ('In Entwicklung');
INSERT INTO project_status (name) VALUES ('Abgeschlossen');

INSERT INTO role (name) VALUES ('Administrator');
INSERT INTO role (name) VALUES ('Leitung');
INSERT INTO role (name) VALUES ('Mitarbeiter');

INSERT INTO `user` (firstname, lastname, password, email, notice,
hourly_rate, hours_of_work_per_day, salutation,
title, `role_id`) VALUES ('Administrator', 'Thunderball', 'c08f58d42936812dada8b1803b91f7f2', 'admin@thunderball', '', 50, 8, 0, '', 1);