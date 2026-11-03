# PeoplePulse HR
PHP MVC HR system: employee management, leave requests, attrition analytics dashboard and a rule-based attrition risk score.

## Setup (XAMPP)
1. Start Apache and MySQL in XAMPP.
2. Copy this folder to C:\xampp\htdocs\hr-system
3. phpMyAdmin > Import > choose database/hr_system.sql > Go (it creates the hr_system database).
4. Open http://localhost/hr-system/
5. Demo logins (password: password): admin, hr, employee
   If login fails, open http://localhost/hr-system/setup.php once, then delete setup.php.

## Structure
index.php (router) - config/ - core/ (Database, Controller, helpers) - models/ - controllers/ - views/ - assets/ - database/

## Data
All data is fictional and generated for demonstration (400 employees, 235 leave requests).
database/employees_dataset.csv is the same employee data in CSV form.
