<?php
include "../../connect.php";
$id = $_GET["id"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Emails Masivos MIA</title>
    <link rel="stylesheet" type="text/css" href="jquery/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="jquery/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="jquery/demo/demo.css">
    
	<script type="text/javascript" src="jquery/jquery-1.6.min.js"></script>
	<script type="text/javascript" src="jquery/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="jquery/locale/easyui-lang-es.js"></script>
</head>
<body>
    <h2>Env&iacute;o Masivo de Comunicaciones V&iacute;a Email</h2>
    <p></p>
    <div style="margin:20px 0;"></div>
		<?php
		$sql = "select tipo, asunto, notificacion, notificados, notificados_efectivos, enviado, notificar 
				from notificaciones a where Nnotificaciones = '$id';"; 
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$arSel = explode(",",$row["notificados"]);
		$efectivos = explode(",",$row["notificados_efectivos"]);
		$enviado = $row["enviado"]==1?"<b><i>SI</i></b>":"<b><i>NO</i></b>";
		$asunto = $row["asunto"];
		$notificacion = $row["notificacion"];
		$tipo = $row["tipo"];

		$sql = "SELECT valor2 AS tipo FROM parametro WHERE codigo = '016' AND valor1 = '" . $row["notificar"] . "';"; 
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);

		$notificar = $row["tipo"];

    echo '<ul class="easyui-datalist" title="Comunicaci&oacute;n para '.$notificar.'" lines="true" style="width:800px;height:400px">';
		
		foreach($arSel as $ar)
		{
			$enviado = "NO";
			foreach ($efectivos as $efc) {
				if($efc == $ar) $enviado = "SI";
			}
			if(trim($ar)!="") echo '<li value="'.$ar.'">'.$ar.' -- ENVIADO: '.$enviado.'</li>';
		}
		?>
    </ul>
	
	<br />
	<div>Asunto: <input class="easyui-textbox" data-options="editable:false" style="width:600px;height:32px" name="asunto" value="<?php echo $asunto; ?>"></div>
	<br />
	<div style="width:800px;height:300px;overflow:scroll"><?php echo $notificacion; ?></div>
	
	<div style="text-align:left;padding:5px">
		<a href="javascript:void(0)" class="easyui-linkbutton" onClick="returnForm()">Salir</a>
	</div>

	<script>	
        function returnForm(){
            window.location="../../notificacioneslist.php";
        }
    </script>
</body>
</html>
