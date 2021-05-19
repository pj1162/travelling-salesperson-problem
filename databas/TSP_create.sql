-- Created by Vertabelo (http://vertabelo.com)
-- Last modification date: 2021-05-17 19:50:12.074

-- tables
-- Table: Destination
CREATE TABLE Destination (
    id int NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    latitude decimal(8,6) NOT NULL,
    longitude decimal(9,6) NOT NULL,
    journey_id int NOT NULL,
    CONSTRAINT Destination_pk PRIMARY KEY (id)
);

-- Table: From
CREATE TABLE `From` (
    id int NOT NULL AUTO_INCREMENT,
    destination_id int NOT NULL,
    CONSTRAINT From_pk PRIMARY KEY (id)
);

-- Table: Journey
CREATE TABLE Journey (
    id int NOT NULL AUTO_INCREMENT,
    CONSTRAINT Journey_pk PRIMARY KEY (id)
);

-- Table: To
CREATE TABLE `To` (
    id int NOT NULL AUTO_INCREMENT,
    destination_id int NOT NULL,
    CONSTRAINT To_pk PRIMARY KEY (id)
);

-- Table: to_from
CREATE TABLE to_from (
    id int NOT NULL AUTO_INCREMENT,
    length decimal(7,2) NOT NULL,
    to_id int NOT NULL,
    from_id int NOT NULL,
    CONSTRAINT to_from_pk PRIMARY KEY (id)
);

-- foreign keys
-- Reference: From_Destination (table: From)
ALTER TABLE `From` ADD CONSTRAINT From_Destination FOREIGN KEY From_Destination (destination_id)
    REFERENCES Destination (id);

-- Reference: To_Destination (table: To)
ALTER TABLE `To` ADD CONSTRAINT To_Destination FOREIGN KEY To_Destination (destination_id)
    REFERENCES Destination (id);

-- Reference: destination_journey (table: Destination)
ALTER TABLE Destination ADD CONSTRAINT destination_journey FOREIGN KEY destination_journey (journey_id)
    REFERENCES Journey (id);

-- Reference: to_from_From (table: to_from)
ALTER TABLE to_from ADD CONSTRAINT to_from_From FOREIGN KEY to_from_From (from_id)
    REFERENCES `From` (id);

-- Reference: to_from_To (table: to_from)
ALTER TABLE to_from ADD CONSTRAINT to_from_To FOREIGN KEY to_from_To (to_id)
    REFERENCES `To` (id);

-- End of file.

