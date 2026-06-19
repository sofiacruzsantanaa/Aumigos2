<?php
session_start();
session_unset();
session_destroy();
header("Location: /CODIGOSWELISON/Aumigos/login.php");
exit();
?>