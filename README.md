# VueJS-OSPS Group 5 Project

## Overview

Online Smart Parking System is a Parking Slot Booking System developed using Vue.js for the frontend and PHP (OOP) for the backend. The system allows users to register, log in, browse available parking slots, create invoices, make payments, and manage their bookings and vehicles.

## Requirements

- XAMPP Version 8.2.12 or Older
- Web Browser (Latest version of Chrome, Firefox, or Edge recommended)

## Setup Instructions

### 1. Import Database

1. Start XAMPP and ensure that Apache and MySQL services are running.
2. Open your web browser and go to phpMyAdmin.
3. Create a new database named `cos30043`.
4. Click on the Import tab in phpMyAdmin.
5. Click on Choose File and select the `cos30043.sql` file located in the database folder of the project.
6. Click on Go to import the database.

### 2. Clone Project

1. Navigate to the `xampp/htdocs` directory on your system.
2. Open a terminal or command prompt in this directory.
3. Run the following command to clone the project repository:
   
   ```sh
   git clone https://github.com/yourusername/VueJs-OSPS-group5.git
5. After cloning, the project directory VueJs-OSPS-group5 should be present inside xampp/htdocs


### 3. Configure Database

1. Open the project directory VueJs-OSPS-group5.
2. Navigate to backend(OOP)/configs.
3. Open the db.php file in a text editor.
4. Adjust the database configuration to match your local setup. Ensure the following details are correct:
   
    ```sh
    $host = 'localhost';
    $dbname = 'cos30043';
    $username = 'root';
    $password = '';
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
    
 ### 4. Start the Application
1. Open your web browser and navigate to http://localhost/VueJs-OSPS-group5
