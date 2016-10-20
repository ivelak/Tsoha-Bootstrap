

INSERT INTO Oblivious (name, password) VALUES ('Lennart', 'salasana');
INSERT INTO Oblivious (name, password) VALUES ('Pasi', 'salasana');

INSERT INTO Task (name, description, oblivious_id, deadline, added, done) VALUES ('Roskat', 'Vie roskat', '2', '2016-11-11', NOW(), TRUE);
INSERT INTO Task (name, description, oblivious_id, deadline, added, done) VALUES ('Koiran kusetus', 'Kuseta koira, mielellään ulkona', '2', '2016-10-14', NOW(), FALSE);

INSERT INTO TaskCategory (name, oblivious_id) VALUES('Kotityöt', '2');
INSERT INTO TaskCategory (name, oblivious_id) VALUES('Sosiaaliset suhteet', '2');
INSERT INTO TaskCategory (name, oblivious_id) VALUES('Autohommat', '2');
INSERT INTO TaskCategory (name, oblivious_id) VALUES('Koulu', '2');

INSERT INTO TaskCategoryUnion (task_id, taskcategory_id) VALUES ('2','1');
INSERT INTO TaskCategoryUnion (task_id, taskcategory_id) VALUES ('2','2');
INSERT INTO TaskCategoryUnion (task_id, taskcategory_id) VALUES ('1','2');