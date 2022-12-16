# Clinic System Prototype

## Trying Out the System
There are two main ways to access the system:
1. Access hosted instance
2. Install on local machine

## Accessing Hosted Instance
Use [this link](http://falitsehgal.com) to access the system. It will prompt you to turn back, as the system is running on HTTP, which is technically insecure, but click "I'm Sure", and you should be able to access the system. 

## Local Installation and Setup 
1. Download XAMPP using the following link: [XAMPP](https://www.apachefriends.org/download.html)
2. Either clone this repo or download and extract the files to within the XAMPP htdocs directory (default:`C:\xampp\htdocs`) 
3. Copy all lines in `clinicTable.txt` and paste into MySQL terminal to create database tables 
4. Copy all lines in `clinicData.txt` and paste into MySQL terminal to populate the database 
5. By default, XAMPP sets the root password for MySQL to nothing. Our hosted instance has a non-null password, so the MySQL password will need to be changed in `inc/db.php` & `dbconnection.php` to the following values:
#### inc/db.php: Line 16
```
self::$con = new PDO( 'mysql:charset=utf8mb4;host=localhost;port=3306;dbname=Clinic', 'root', '' );
```

#### dbconnection.php: Line 4
```
$servername = "localhost";
$username = "root";
$password = '';
$dbname = "Clinic";
```
6. Once the password is set in those 2 files, enter this URL into your browser: `localhost`. The following page should appear:

<img src=".\readmeassets\firefox_licJcInCRV.png">