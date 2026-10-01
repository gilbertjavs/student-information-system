# Student Information Management System

A simple CRUD web app for recording student information online.
Built with HTML, CSS, JavaScript, PHP, MySQLi and MySQL. Hosted on InfinityFree, source on GitHub.

## Features
- **Create** - add a student (server-side and client-side validation, duplicate student numbers blocked)
- **Read** - table of all students with search (name, number, email), course filter, and pagination
- **Update** - edit any student and save changes
- **Delete** - delete with a confirmation prompt (POST only)
- **MySQLi** - every query uses prepared statements

## Project structure
```
config/db.php        MySQLi connection + helpers + validation
includes/            header, footer, shared form
assets/css, assets/js
index.php            Read (list, search, filter)
create.php           Create
edit.php             Update
delete.php           Delete
database.sql         Table + sample data
```

## Run on localhost (XAMPP)
1. Copy this folder to `C:\xampp\htdocs\student_system`.
2. Start Apache and MySQL in the XAMPP Control Panel.
3. Open http://localhost/phpmyadmin, click **Import**, choose `database.sql`, and run it.
4. Open http://localhost/student_system/

## Deploy on InfinityFree
1. Sign up at infinityfree.com and create a hosting account (you get a free subdomain).
2. In the control panel open **MySQL Databases**, create a database, and note the host, username, password and full database name.
3. Open **phpMyAdmin** for that database, click **Import**, and upload `database.sql`.
   - Delete the first two lines (`CREATE DATABASE ...` and `USE ...`) before importing, since InfinityFree gives you a database already.
4. Edit `config/db.php` with the InfinityFree host, username, password and database name.
5. Upload everything to the `htdocs` folder using the **Online File Manager** or an FTP client (FileZilla).
6. Open your public URL, e.g. `http://yourname.infinityfreeapp.com`.

## Push to GitHub
```
git init
git add .
git commit -m "Student Information Management System"
git branch -M main
git remote add origin https://github.com/<your-username>/student-system.git
git push -u origin main
```
Tip: before pushing, put placeholder values in `config/db.php` so your real hosting password is not public.

## Online testing checklist
- [ ] Add a student from your phone
- [ ] Ask a classmate to open the URL and add a record
- [ ] Edit a record and confirm the change appears
- [ ] Delete a record and confirm it is gone
- [ ] Search by name and filter by course
