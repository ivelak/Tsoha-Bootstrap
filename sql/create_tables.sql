

CREATE TABLE Kayttaja(
    id SERIAL PRIMARY KEY,
    nimi varchar(15) NOT NULL,
    salasana varchar(50) NOT NULL
);


CREATE TABLE Askare(
    id SERIAL PRIMARY KEY,
    kayttaja_id INTEGER REFERENCES Kayttaja(id),
    nimi varchar(50) NOT NULL,
    kuvaus varchar(500)
);


CREATE TABLE Askareluokka(
    id SERIAL PRIMARY KEY,
    nimi varchar(50) NOT NULL,
    kuvaus varchar(500)
);

CREATE TABLE Askareluokkaliitos(
    askare_id INTEGER REFERENCES Askare(id),
    askareluokka_id INTEGER REFERENCES Askareluokka(id)
);

CREATE TABLE Tarkeysaste(
    askare_id INTEGER REFERENCES Askare(id),
    tarkeys INT NOT NULL
);

CREATE TABLE Deadline(
    dedis DATE NOT NULL,
    askare_id INTEGER REFERENCES Askare(id)
);
    