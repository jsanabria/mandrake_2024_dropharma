<?php
include "../connect.php";
include "../funciones.php";

$item = intval($_REQUEST["item"]);

// 1. Consultar el estado actual del item antes de modificarlo
$sqlCheck = "SELECT check_ne, articulo, costo_unidad FROM entradas_salidas WHERE id = $item;";
$rsCheck = mysqli_query($link, $sqlCheck);
$rowCheck = mysqli_fetch_array($rsCheck);

$estadoActual = $rowCheck["check_ne"];
$articulo = $rowCheck["articulo"];
$costo_unidad = $rowCheck["costo_unidad"];

// 2. Cambiar el estado de check_ne (Alternar entre 'S' y 'N')
$sqlToggle = "UPDATE entradas_salidas SET check_ne = if(check_ne='S', 'N', 'S') WHERE id = $item;";
mysqli_query($link, $sqlToggle);

// 3. Consultar el parámetro de tipo de costo (código '113')
$sqlParam = "SELECT valor1 AS tipo_costo FROM parametro WHERE codigo = '113' AND valor3 = 'default' LIMIT 1;";
$rsParam = mysqli_query($link, $sqlParam);

if (mysqli_num_rows($rsParam) == 0) {
    // Si no existe, inicializamos las opciones por defecto
    $sqlInsertParam = "
        INSERT INTO parametro (`codigo`, `descripcion`, `valor1`, `valor2`, `valor3`) VALUES 
        ('113', 'TIPO DE COSTO', 'A', 'SIN ACTUALIZAR COSTO', 'default'),
        ('113', 'TIPO DE COSTO', 'B', 'ULTIMO COSTO', ''),
        ('113', 'TIPO DE COSTO', 'C', 'COSTO PROMEDIO', ''),
        ('113', 'TIPO DE COSTO', 'D', 'COSTO PROMEDIO PONDERADO', '');
    ";
    mysqli_query($link, $sqlInsertParam);
    
    // Por defecto asignamos 'A' o la opción que prefieras si se acaba de crear
    $tipo_costo = 'A'; 
} else {
    $rowParam = mysqli_fetch_array($rsParam);
    $tipo_costo = $rowParam["tipo_costo"];
}

// 4. Validar que la acción sea una ACTIVACIÓN NUEVA 
// Es decir, estaba en 'N' (o vacío/null) y ahora el usuario lo está marcando a 'S'.
// Si estaba en 'S', significa que lo están desmarcando, por lo que NO actualizamos el costo.
if ($estadoActual != 'S') {

    // Bifurcación con switch según el tipo de costo configurado
    switch ($tipo_costo) {
        case 'A':
            // SIN ACTUALIZAR COSTO
            break;

        case 'B':
            // ULTIMO COSTO (Actualiza directamente la tabla articulo)
            /*$sqlUpdateArticulo = "UPDATE articulo SET ultimo_costo = $costo_unidad WHERE id = '$articulo';";
            mysqli_query($link, $sqlUpdateArticulo);*/
            CostoUltimo($articulo, $link);
            break;

        case 'C':
            // COSTO PROMEDIO (Descomentar o adaptar según tu función existente)
            CostoPromedio($articulo, $link);
            break;

        case 'D':
            // COSTO PROMEDIO PONDERADO (Lógica personalizada aquí si aplica)
        	CostoPromedioPonderado($articulo, $link);
            break;
    }

}

echo json_encode('{"Salida":"OK"}', JSON_UNESCAPED_UNICODE);
?>