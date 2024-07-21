<?php
$hashedPassword = '$2y$10$QplKB8Ry6nP0lwkG0p1KTuAKdH2NknxyKZ6MRKfskPv4u.6Gku4bq'; // Replace with the hashed password from the DB
$password = 'password'; // Plain password

if (password_verify($password, $hashedPassword)) {
    echo 'Password is valid!';
} else {
    echo 'Invalid password.';
}
?>
