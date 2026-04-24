<?php   
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

/*** CONFIGURACIÓN ***/
$ftp_server = "ftp.icompras360.net";
$ftp_user_name = "icompras360_411939188"; 
$ftp_user_pass = "411939188$.*";   

// Rutas Locales
$local_path_entradas = "/home2/dropharm/dropharmadm/ftpexportar2/entradas/";
$local_path_salidas  = "/home2/dropharm/dropharmadm/ftpexportar2/salidas/";

// Rutas Remotas (iCompras)
$remote_path_entrada = "/entrada/";
$remote_path_salida  = "salida/";

// Conexión
$conn_id = ftp_ssl_connect($ftp_server, 21, 90) or die("No se pudo conectar al servidor FTP.");
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("Credenciales incorrectas.");

// ACTIVAR MODO PASIVO (Esto solucionó el error 115)
ftp_pasv($conn_id, true);

echo "--- Inicio de Proceso FTP ---\n";

/** 1. SUBIDA DE ARCHIVOS (Inventario y Cliente) **/
$archivos_a_subir = ["inventario.txt", "cliente.txt"];

foreach ($archivos_a_subir as $archivo) {
    $file_source = $local_path_entradas . $archivo;
    $file_dest   = $remote_path_entrada . $archivo;

    if (file_exists($file_source)) {
        if (@ftp_put($conn_id, $file_dest, $file_source, FTP_BINARY)) {
            echo "SUBIDA EXITOSA: $archivo\n";
        } else {
            echo "ERROR DE SUBIDA: $archivo\n";
        }
    } else {
        echo "ARCHIVO NO ENCONTRADO (LOCAL): $archivo\n";
    }
}

echo "-------------------------------\n";

/** 2. DESCARGA Y LIMPIEZA DE SALIDAS **/
$contents = ftp_nlist($conn_id, $remote_path_salida);

if (is_array($contents)) {
    foreach ($contents as $remote_file) {
        // Limpiamos el nombre para el destino local
        $file_name = basename($remote_file);
        
        if ($file_name != '.' && $file_name != '..') {
            $dest_local_file = $local_path_salidas . $file_name;
            
            // Intentar descargar
            if (@ftp_get($conn_id, $dest_local_file, $remote_file, FTP_BINARY)) {
                echo "DESCARGADO: $file_name\n";
                
                // Borrar del servidor solo si se descargó correctamente
                if (@ftp_delete($conn_id, $remote_file)) {
                    echo "BORRADO REMOTO: $file_name\n";
                } else {
                    echo "ERROR AL BORRAR: $file_name\n";
                }
            } else {
                echo "ERROR AL DESCARGAR: $file_name\n";
            }
        }
    }
} else {
    echo "No hay archivos pendientes en la carpeta de salida.\n";
}

// Cerrar conexión de forma silenciosa
@ftp_close($conn_id);
echo "--- Proceso Finalizado ---\n";
?>