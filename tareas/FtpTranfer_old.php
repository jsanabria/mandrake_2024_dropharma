<?php   
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

/*** Sube archivos a iCompras ***/

// connection settings
$ftp_server = "ftp.icompras360.net";  //address of ftp server (leave out ftp://)
$ftp_user_name = "icompras360_411939188"; // Username
$ftp_user_pass = "411939188$.*";   // Password
$conn_id = ftp_connect($ftp_server);        // set up basic connection

// login with username and password, or give invalid user message
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("<h1>You do not have access to this ftp server!</h1>");

$Path = "/home2/dropharm/dropharmadm/ftpexportar2/entradas/";

// Inventario 
$File = "inventario.txt";
$myFileName = $Path . $File; //Retrieve file path and file name    
$destination_file = "/entrada/" . $File;  //where you want to throw the file on the webserver (relative to your login dir)

$upload = ftp_put($conn_id, $destination_file, $myFileName, FTP_BINARY);  // upload the file
if (!$upload) {  // check upload status
    echo "<h2>FTP upload of $myFileName has failed!</h2> <br />";
}

$File = "cliente.txt";
$myFileName = $Path . $File; //Retrieve file path and file name    
$destination_file = "/entrada/" . $File;  //where you want to throw the file on the webserver (relative to your login dir)

$upload = ftp_put($conn_id, $destination_file, $myFileName, FTP_BINARY);  // upload the file
if (!$upload) {  // check upload status
    echo "<h2>FTP upload of $myFileName has failed!</h2> <br />";
}

///////////////////////////////

/*** Decarga archivos de iCompras ***/

$destination_file = "/home2/dropharm/dropharmadm/ftpexportar2/salidas/";
$Path = "salida/"; 

$contents = ftp_nlist($conn_id, $Path);
// var_dump($contents);

foreach ($contents as $file) {
    $file = str_replace("salida/", "", $file);
    if ($file != '.' and $file != '..') {
        ftp_get($conn_id, $destination_file . $file, "$Path" . $file, FTP_BINARY);
    }
}


foreach ($contents as $file) {
    $file = str_replace("salida/", "", $file);
    if ($file != '.' and $file != '..') {
        ftp_delete($conn_id, "$Path" . $file);
    }
}


ftp_close($conn_id); 
?>