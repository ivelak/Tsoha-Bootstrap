

INSERT INTO Oblivious (name, password) VALUES ('Lennart', 'salasana');
INSERT INTO Oblivious (name, password) VALUES ('Pasi', 'salasana');

INSERT INTO Task (name, description, oblivious_id) VALUES ('Roskat', 'Vie roskat', '2');
INSERT INTO Task (name, description, oblivious_id) VALUES ('Koiran kusetus', 'Kuseta koira, mielellään ulkona', '2');

INSERT INTO TaskCategory (name, oblivious_id) VALUES('Kotityöt', '2');
INSERT INTO TaskCategory (name, oblivious_id) VALUES('Sosiaaliset suhteet', '2');
INSERT INTO TaskCategory (name, oblivious_id) VALUES('Autohommat', '2');
INSERT INTO TaskCategory (name, oblivious_id) VALUES('Koulu', '2');