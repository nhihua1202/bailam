Hanoi Rental - Local XAMPP version
---------------------------------

1) Import database:
   - Open phpMyAdmin, run the SQL in hanoi_rental.sql (copy & paste).
   - The SQL creates database 'hanoi_rental' and an admin user (email: admin@local, password: admin123).
   - If php CLI isn't available to generate a real hash, update the password hash in the SQL to one you create.

2) Place this folder inside your XAMPP htdocs (or configure Apache to point to it):
   For example: C:\xampp\htdocs\hanoi_rental_v3_fixed\

3) Ensure folder 'uploads' is writable by Apache (on Windows it normally is).

4) Visit http://localhost/hanoi_rental_v3_fixed/index.php

Files created by the assistant. If you want further feature polish (better search, ownership, pagination),
tell me and I'll update files.
