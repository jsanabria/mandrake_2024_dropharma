<?php
session_start();
// $_SESSION["strcon"] = $_REQUEST["db"];
// setcookie("strcon", "", time()-60*60*24);
setcookie("strcon", $_REQUEST["db"], time()+60*60*24);
header("Location: login");
?>