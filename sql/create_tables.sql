

CREATE TABLE Oblivious(
    id SERIAL PRIMARY KEY,
    name varchar(15) NOT NULL,
    password varchar(50) NOT NULL
);


CREATE TABLE Task(
    id SERIAL PRIMARY KEY,
    oblivious_id INTEGER REFERENCES Oblivious(id),
    name varchar(50) NOT NULL,
    description varchar(500),
    done boolean DEFAULT FALSE
);


CREATE TABLE TaskCategory(
    id SERIAL PRIMARY KEY,
    name varchar(50) NOT NULL,
    oblivious_id INTEGER REFERENCES Oblivious(id)
);

CREATE TABLE TaskCategoryUnion(
    task_id INTEGER REFERENCES Task(id),
    taskcategory_id INTEGER REFERENCES TaskCategory(id)
);

CREATE TABLE Importance(
    task_id INTEGER REFERENCES Task(id),
    importance INT NOT NULL
);

CREATE TABLE Deadline(
    deadline DATE NOT NULL,
    task_id INTEGER REFERENCES Task(id)
);
    