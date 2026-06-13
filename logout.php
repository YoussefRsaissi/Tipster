<?php
session_start();
session_unset();   // smaže všechny session proměnné
session_destroy(); // ukončí session
header("Location: login.php"); // přesměruje na login
exit;
