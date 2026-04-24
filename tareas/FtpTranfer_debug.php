<?php   
ini_set('display_errors', '1');
error_reporting(E_ALL);

$ftp_server = "ftp.icompras360.net";
$ftp_user_name = "icompras360_411939188"; 
$ftp_user_pass = "411939188$.*";   

$conn_id = ftp_ssl_connect($ftp_server, 21, 90) or die("CRÍTICO: No se conectó al Host.");
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("CRÍTICO: Login fallido.");

ftp_pasv($conn_id, true);

echo "--- Iniciando Debug de Subida ---\n";

$local_path = "/home2/dropharm/dropharmadm/ftpexportar2/entradas/";
$archivos = ["inventario.txt", "cliente.txt"];

foreach ($archivos as $archivo) {
    $source = $local_path . $archivo;
    $dest   = "/entrada/" . $archivo;

    if (file_exists($source)) {
        echo "\nProcesando: $archivo...\n";
        
        // --- AQUÍ CAPTURAMOS EL ERROR REAL ---
        // Iniciamos un buffer para atrapar advertencias de PHP
        ob_start(); 
        $result = ftp_put($conn_id, $dest, $source, FTP_BINARY);
        $php_errormsg = ob_get_clean(); 

        if (!$result) {
            echo "❌ ERROR AL SUBIR $archivo\n";
            echo "Detalle técnico: " . ($php_errormsg ? strip_tags($php_errormsg) : "El servidor cerró la conexión sin mensaje.") . "\n";
            
            // Intentamos ver si hay un error de sistema registrado
            $last_error = error_get_last();
            if ($last_error && strpos($last_error['message'], 'ftp_put') !== false) {
                echo "Mensaje del Kernel PHP: " . $last_error['message'] . "\n";
            }
        } else {
            echo "✅ SUBIDA EXITOSA: $archivo\n";
        }
    } else {
        echo "⚠️ No existe el archivo local: $source\n";
    }
}

@ftp_close($conn_id);
echo "\n--- Fin del reporte ---\n";
?>