<?php
session_start();
// 60*60*24*365 = 31.536.000 segundos (1 año)
// setcookie("strcon", $_REQUEST["db"], time()+60*60*24);
setcookie("strcon", $_REQUEST["db"], time() + (60 * 60 * 24 * 365), "/"); 
header("Location: login");
exit();
?>