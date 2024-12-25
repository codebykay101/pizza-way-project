<?php
session_start();
session_unset();
session_destroy();
header('Location: /projects/PizzaWay/profile/sign-in.html');
exit;
?>
