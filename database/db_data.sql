/* DATABASE inserts for "vacation_rental_db" */

-- users
INSERT INTO vacation_rental_db.users (email, password, forename, surname) VALUES ('admin@admin.com', '$2y$10$j3rZkvF.yhHt9IqpKgH4iuzJiEkRCrxBT2PCEBBgbG1xcGLx23YfW', 'admina', 'admin');

-- typetables
INSERT INTO vacation_rental_db.typetables (id, type) VALUES (1, 'front');
INSERT INTO vacation_rental_db.typetables (id, type) VALUES (2, 'layout');
INSERT INTO vacation_rental_db.typetables (id, type) VALUES (3, 'option');
INSERT INTO vacation_rental_db.typetables (id, type) VALUES (4, 'other');

-- features
INSERT INTO vacation_rental_db.features (name, category) VALUES ('Föhn', 'Bad');
INSERT INTO vacation_rental_db.features (name, category) VALUES ('Badewanne', 'Bad');
INSERT INTO vacation_rental_db.features (name, category) VALUES ('Dusche', 'Bad');
INSERT INTO vacation_rental_db.features (name, category) VALUES ('Waschmaschine', 'Bad');
INSERT INTO vacation_rental_db.features (name, category) VALUES ('Spülmaschine', 'Küche');
INSERT INTO vacation_rental_db.features (name, category) VALUES ('Toaster', 'Küche');
INSERT INTO vacation_rental_db.features (name, category) VALUES ('Mikrowelle', 'Küche');
INSERT INTO vacation_rental_db.features (name, category) VALUES ('Eierkocher', 'Küche');
INSERT INTO vacation_rental_db.features (name, category) VALUES ('WLan', 'Multimedia');
INSERT INTO vacation_rental_db.features (name, category) VALUES ('TV', 'Multimedia');
INSERT INTO vacation_rental_db.features (name, category) VALUES ('Garten', 'Outdoor');
INSERT INTO vacation_rental_db.features (name, category) VALUES ('Schaukel', 'Outdoor');
INSERT INTO vacation_rental_db.features (name, category) VALUES ('Strandkorb', 'Outdoor');
INSERT INTO vacation_rental_db.features (name, category) VALUES ('Pool', 'Outdoor');
INSERT INTO vacation_rental_db.features (name, category) VALUES ('Parkplatz', 'Outdoor');
INSERT INTO vacation_rental_db.features (name, category) VALUES ('Auto-Ladestation', 'Outdoor');
INSERT INTO vacation_rental_db.features (name, category) VALUES ('Klimaanlage', 'Sonstiges');
INSERT INTO vacation_rental_db.features (name, category) VALUES ('Grill', 'Sonstiges');
INSERT INTO vacation_rental_db.features (name, category) VALUES ('Kinderstuhl', 'Sonstiges');
INSERT INTO vacation_rental_db.features (name, category) VALUES ('Haustiere erlaubt', 'Sonstiges');
INSERT INTO vacation_rental_db.features (name, category) VALUES ('Meerblick', 'Sonstiges');
INSERT INTO vacation_rental_db.features (name, category) VALUES ('Nichtraucher', 'Sonstiges');
INSERT INTO vacation_rental_db.features (name, category) VALUES ('Whirlpool', 'Wellness');
INSERT INTO vacation_rental_db.features (name, category) VALUES ('Sauna', 'Wellness');
INSERT INTO vacation_rental_db.features (name, category) VALUES ('Kamin', 'Wellness');
INSERT INTO vacation_rental_db.features (name, category) VALUES ('Fußbodenheizung', 'Wellness');
INSERT INTO vacation_rental_db.features (name, category) VALUES ('Massagesessel', 'Wellness');

-- houses

-- images

-- options

-- tags

-- bookings

-- bookingpositions

-- END