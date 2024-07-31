USE cos30043;

CREATE TABLE IF NOT EXISTS locations (

    locationid INT AUTO_INCREMENT PRIMARY KEY,
    locationname VARCHAR(255) NOT NULL,
    isallowcar BINARY NOT NULL,
    isallowbikes BINARY NOT NULL
);

CREATE TABLE IF NOT EXISTS types (

    typeid INT AUTO_INCREMENT PRIMARY KEY,
    typename VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS parkingslots (
    parkingslotid INT AUTO_INCREMENT PRIMARY KEY,
    locationid INT NOT NULL,
    typeid INT NOT NULL,
    slotname VARCHAR(50) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    isavailable BINARY NOT NULL,
    FOREIGN KEY (locationid) REFERENCES locations(locationid),
    FOREIGN KEY (typeid) REFERENCES types(typeid)
);

CREATE TABLE IF NOT EXISTS users (
    userid INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    dateofbirth DATE NOT NULL,
    street VARCHAR(50) NOT NULL,
    district VARCHAR(50) NOT NULL,
    city VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS reservations (
    reservationid INT AUTO_INCREMENT PRIMARY KEY,
    userid INT NOT NULL,
    parkingslotid INT NOT NULL,
    startdate DATE NOT NULL,
    duration INT NOT NULL,
    FOREIGN KEY (userid) REFERENCES users(userid),
    FOREIGN KEY (parkingslotid) REFERENCES parkingslots(parkingslotid)
);

CREATE TABLE IF NOT EXISTS cart (
    cartid INT AUTO_INCREMENT PRIMARY KEY,
    parkingslotid INT NOT NULL,
    userid INT NOT NULL,
    duration INT NOT NULL,
    startdate DATE NOT NULL,
    FOREIGN KEY (userid) REFERENCES users(userid),
    FOREIGN KEY (parkingslotid) REFERENCES parkingslots(parkingslotid)
);

INSERT INTO locations (locationname, isallowcar, isallowbikes) VALUES
('Downtown Garage', 1, 0),
('Suburb Lot', 1, 1),
('City Center', 1, 1),
('Mall Parking', 1, 0),
('Stadium Lot', 1, 1),
('Airport Parking', 1, 0),
('Train Station', 0, 1),
('Beach Parking', 1, 1),
('Museum Lot', 0, 1),
('University Parking', 1, 1);

INSERT INTO types (typename) VALUES
('car'),
('bike');

INSERT INTO parkingslots (locationid, typeid, slotname, price, isavailable) VALUES
(1, 1, 'A1', 3.99, 1),
(1, 1, 'A2', 3.99, 0),
(2, 2, 'B1', 3.99, 1),
(2, 1, 'B2', 3.99, 1),
(3, 1, 'C1', 3.99, 1),
(3, 2, 'C2', 3.99, 0),
(4, 1, 'D1', 3.99, 1),
(4, 1, 'D2', 3.99, 1),
(5, 1, 'E1', 3.99, 0),
(5, 2, 'E2', 3.99, 1),
(6, 1, 'F1', 3.99, 1),
(6, 1, 'F2', 3.99, 1),
(7, 2, 'G1', 3.99, 0),
(7, 2, 'G2', 3.99, 1),
(8, 1, 'H1', 3.99, 1),
(8, 2, 'H2', 3.99, 0),
(9, 2, 'I1', 3.99, 1),
(9, 2, 'I2', 3.99, 1),
(10, 1, 'J1', 3.99, 0),
(10, 1, 'J2', 3.99, 1);
