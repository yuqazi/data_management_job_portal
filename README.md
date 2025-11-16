#Data Management Job portal
Job Portal Data Management System

A web-based platform designed to manage job listings, applicant submissions, and administrative review. The system demonstrates key concepts in database management, server-side scripting, and dynamic website development.

Description

  The Job Portal Data Management System is a multi-tier application implemented using PHP, MySQL, HTML, CSS, and JavaScript. It provides a structured environment where administrators can create and manage job postings, and users can browse these postings and submit applications with their personal information and documents.

This project is intended as an academic demonstration of:
  - Database-driven web applications
  - CRUD operations in a real-world context
  - Client-server communication
  - Form processing and persistent data storage
  - Basic security considerations (input validation, upload handling)

The system simulates a simplified version of modern recruitment platforms and can serve as a foundation for further development in software engineering coursework.

Getting Started

  Dependencies
    Before installing the program, ensure the following prerequisites are met:

  Operating System Requirements
    - Windows 10 or Windows 11
    - Ubuntu 20.04+, Debian 11+, Fedora 34+ (or any modern Linux distribution)

  Required Software
    - Web Server: Apache 2.4+
    - PHP: Version 8.0 or higher
        - Required PHP Extensions:
          - mysqli
          - pdo_mysql
          - session
          - openssl
          - json
          
    - Database: MySQL 5.7+
    - Optional Tools:
      - Git (version control)
      - Composer (future dependency management)
      - phpMyAdmin (database UI)

Installing
1. Downloading the Program
  1.  Extract the provided ZIP file:
     - Data_management_job_portal-main.zip

  2. Move the extracted project folder into your web server directory:
    - Windows (XAMPP): C:\xampp\htdocs\job_portal\
    - Linux (LAMP): /var/www/html/job_portal/

2. Database Setup
  1. Start MySQL
  2. Open phpMyAdmin or your SQL terminal
  3. Create the database: CREATE DATABASE job_portal;
  4. Import schema.sql (if included)

3. Configuration File Modification
  Open the file: config.php
  Modify database credentials appropriately:
    $db_host = "localhost";
    $db_user = "root";
    $db_pass = "";         // Linux users may need to set a password
    $db_name = "job_portal";

Executing Program
  On Windows (XAMPP)
    1. Open XAMPP Control Panel
    2. Start:
        Apache
        MySQL
    3. Navigate in your browser to:
        http://localhost/job_portal/
    On Linux (LAMP)
      Start services:
        sudo systemctl start apache2
        sudo systemctl start mysql
      Restart if needed:
        sudo systemctl restart apache2
      Access the program:
        http://your-server-ip/job_portal/

Step-by-Step Execution Summary
  1. Install required software
  2. Extract the project folder to the web root
  3. Create job_portal database
  4. Import schema
  5. Configure config.php
  6. Start Apache & MySQL
  7. Open browser and navigate to the job portal

