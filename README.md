📘 Student Registration System – README

📌 Overview

The Student Registration System is a simple PHP–MySQL web application that allows users to:

Register students, Select the class they belong to, Store the records in a MySQL database, Search students by class and Display all student records in a clean tabular format.

This project is ideal for beginners learning PHP, MySQL, HTML, CSS, and basic CRUD operations.

It also demonstrates form handling, prepared statements, joins, and clean UI design without any frameworks.

🚀 Features:

✅ Student Registration Form
Add student details:
Student Name
Father’s Name
Mother’s Name
Email ID
Date of Birth
Class (dropdown fetched dynamically from the database)

✅ Class Management
Add new classes (e.g., Section One, Section Two, etc.)
Classes are stored in a separate class table
Class dropdown automatically updates based on database entries

✅ Student Records Table
Displays all students with:
ID
Name
Father & Mother Name
Email
DOB
Class Name
Styled using simple custom CSS 

✅ Search Functionality
Search students by class name
Uses secure prepared statements
Shows matching results instantly

✅ Clean UI
Built using plain HTML & CSS
Responsive enough for desktop
Consistent color scheme
User-friendly layout

🛠️ Tech Stack
Technology	Purpose

PHP	- Backend logic, form handling

MySQL -	Database for classes & students

HTML/CSS - UI structure and styling

Prepared Statements	Secure - DB queries

VS Code / XAMPP	- Development environment


▶️ How to Run the Project

Install XAMPP or similar local server.

Place the project inside:
htdocs/student-registration/


Import the SQL tables (class & student).

Update your MySQL credentials in:
db_connect.php


Start Apache & MySQL from XAMPP.

Open the project in browser:
http://localhost/student-registration/index.php
