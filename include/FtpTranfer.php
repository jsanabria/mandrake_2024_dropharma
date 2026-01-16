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

$Path = "/home4/drophqsc/dropharmadm.com/ftpexportar2/entradas/";

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

$destination_file = "/home4/drophqsc/dropharmadm.com/ftpexportar2/salidas/";
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

/*
// try to delete $file
if (ftp_delete($conn_id, $destination_file)) {
    echo "$destination_file has been deleted!\n";
} else {
    echo "Could not delete $destination_file!\n";
}
*/
ftp_close($conn_id); // close the FTP stream

/*
$myFile = $_FILES['file']; // This will make an array out of the file information that was stored.
    $file = $myFile['tmp_name'];  //Converts the array into a new string containing the path name on the server where your file is.
    $myFileName = $_POST['MyFile']; //Retrieve file path and file name    
    $myfile_replace = str_replace('\\', '/', $myFileName);    //convert path for use with unix
    $myfile = basename($myfile_replace);    //extract file name from path
    $destination_file = "/".$myfile;  //where you want to throw the file on the webserver (relative to your login dir)
    // connection settings
    $ftp_server = "127.0.0.1";  //address of ftp server (leave out ftp://)
    $ftp_user_name = ""; // Username
    $ftp_user_pass = "";   // Password
    $conn_id = ftp_connect($ftp_server);        // set up basic connection
    // login with username and password, or give invalid user message
    $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("<h1>You do not have access to this ftp server!</h1>");
    $upload = ftp_put($conn_id, $destination_file, $file, FTP_BINARY);  // upload the file
    if (!$upload) {  // check upload status
        echo "<h2>FTP upload of $myFileName has failed!</h2> <br />";
    }

    // try to delete $file
    if (ftp_delete($conn_id, $destination_file)) {
        echo "$destination_file has been deleted!\n";
    } else {
        echo "Could not delete $destination_file!\n";
    }

*/
?>