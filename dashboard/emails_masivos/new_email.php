<?php
require_once("../../connect.php");
$username = $_GET["username"];
$asunto = "";
//require_once("../clases/sesion.php");
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
<style>
  body {
    font-family: Verdana;
    font-size: 11px;
  }
  
  h2 {
    margin-bottom: 0;
  }
  
  small {
    display: block;
    margin-top: 40px;
    font-size: 9px;
  }
  
  small,
  small a {
    color: #666;
  }
  
  a {
    color: #000;
    text-decoration: underline;
    cursor: pointer;
  }
  
  #toolbar [data-wysihtml5-action] {
    float: right;
  }
  
  #toolbar,
  textarea {
    width: 600px;
    padding: 5px;
    -webkit-box-sizing: border-box;
    -ms-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
  }
  
  textarea {
    height: 280px;
    border: 3px solid green;
    font-family: Verdana;
    font-size: 11px;
	border-radius:5px;
  }
  
  textarea:focus {
    color: black;
    border: 2px solid black;
  }
  
  .wysihtml5-command-active {
    font-weight: bold;
  }
  
  [data-wysihtml5-dialog] {
    margin: 5px 0 0;
    padding: 5px;
    border: 1px solid #666;
  }
  
  a[data-wysihtml5-command-value="red"] {
    color: red;
  }
  
  a[data-wysihtml5-command-value="green"] {
    color: green;
  }
  
  a[data-wysihtml5-command-value="blue"] {
    color: blue;
  }
</style>
<body>
    <h2>Env&iacute;o Masivo de Comunicaciones V&iacute;a Email</h2>
    <p>Rellene la Informaci&oacute;n Solicitada para Enviarla.</p>
    <div style="margin:20px 0;"></div>
    <div class="easyui-panel" title="Nuevo Mensaje" style="width:1000px">
        <div style="padding:10px 35px 20px 35px">
        <form id="ff" name="ff" method="post" enctype="multipart/form-data" action="insert_email.php">
            <table cellpadding="5">
            	<!--
                <tr>
                    <td>Mensaje a:</td>
                    <td>
						<select class="easyui-combobox" name="tipo" style="width:600px"
								data-options="prompt:'Enviar Mensaje a',
                                        required:true,
                                        url:'obtener_mensajes_a.php',
                                        method:'get',
                                        valueField:'codigo',
                                        textField:'descripcion',
                                        multiple:false,
                                        panelHeight:'auto',editable:false,hasDownArrow:true">
						</select>
						<input type="hidden" name="username" value="<?php echo $username; ?>" />
					</td>
                </tr>
            	-->
                <tr>
                    <td>Asunto:</td>
                    <td>
						<input class="easyui-textbox" style="width:850px;height:32px" name="asunto" value="<?php echo $asunto; ?>">
						<input type="hidden" name="username" value="<?php echo $username; ?>" />
					</td>
                </tr>
                <tr>
                    <td>Mensaje:</td>
                    <td>
					  <div id="toolbar" style="display: none;">
						<a data-wysihtml5-command="bold" title="CTRL+B"><img src="editor/image/negrita.png" border="0" height="30" width="30" /></a>
						<a data-wysihtml5-command="italic" title="CTRL+I"><img src="editor/image/italica.png" border="0" height="30" width="30" /></a>
						<a data-wysihtml5-command="createLink"><img src="editor/image/enlace.png" border="0" height="30" width="30" /></a>
						<a data-wysihtml5-command="insertImage"><img src="editor/image/imagen.png" border="0" height="30" width="30" /></a>
						<a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h1"><img src="editor/image/titulo.png" border="0" height="30" width="30" /></a>
						<a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h2"><img src="editor/image/subtitulo.png" border="0" height="30" width="30" /></a>
						<a data-wysihtml5-command="insertUnorderedList"><img src="editor/image/lista_no_ordenada.png" border="0" height="30" width="30" /></a>
						<a data-wysihtml5-command="insertOrderedList"><img src="editor/image/lista_ordenada.png" border="0" height="30" width="30" /></a>
						<!--<a data-wysihtml5-command="foreColor" data-wysihtml5-command-value="red"><img src="editor/image/rojo.png" border="0" height="30" width="30" /></a>
						<a data-wysihtml5-command="foreColor" data-wysihtml5-command-value="green"><img src="editor/image/verde.png" border="0" height="30" width="30" /></a>
						<a data-wysihtml5-command="foreColor" data-wysihtml5-command-value="blue"><img src="editor/image/azul.png" border="0" height="30" width="30" /></a>-->
						<a data-wysihtml5-command="insertSpeech">speech</a>
						<a data-wysihtml5-action="change_view"><img src="editor/image/html.png" border="0" height="30" width="30" /></a>
						
						<div data-wysihtml5-dialog="createLink" style="display: none;">
						  <label>
							Link:
							<input data-wysihtml5-dialog-field="href" value="http://">
						  </label>
						  <a data-wysihtml5-dialog-action="save"><img src="editor/image/ok.gif" border="0" /></a>&nbsp;<a data-wysihtml5-dialog-action="cancel"><img src="editor/image/cancelar.gif" border="0" /></a>
						</div>
						
						<div data-wysihtml5-dialog="insertImage" style="display: none;">
						  <label>
							Image:
							<input data-wysihtml5-dialog-field="src" value="http://">
						  </label>
						  <label>
							Align:
							<select data-wysihtml5-dialog-field="className">
							  <option value="">default</option>
							  <option value="wysiwyg-float-left">left</option>
							  <option value="wysiwyg-float-right">right</option>
							</select>
						  </label>
						  <a data-wysihtml5-dialog-action="save"><img src="editor/image/ok.gif" border="0" /></a>&nbsp;<a data-wysihtml5-dialog-action="cancel"><img src="editor/image/cancelar.gif" border="0" /></a>
						</div>
					  </div>
					  <textarea id="notificacion" name="notificacion" placeholder="Coloque el texto ..."></textarea>
					</td>
                </tr>
                <tr>
                    <td>Notificar a:</td>
                    <td>
						<select class="easyui-combobox" id="notificar" name="notificar" style="width:850px" 
								data-options="prompt:'Enviar Mensaje a',
                                        required:true,
                                        url:'obtener_mensajes_a.php',
                                        method:'get',
                                        valueField:'codigo',
                                        textField:'descripcion',
                                        multiple:false,
                                        panelHeight:'auto',editable:false,hasDownArrow:true,onChange:doFilter">
						</select>
					</td>
                </tr>
                <tr>
                    <td>C&eacute;dula / RIF:</td>
                    <td>
						<input class="easyui-textbox" style="width:100px;height:25px" id="cedula" name="cedula" value="">
						Cliente / Proveedor / Usuario / Email:
						<input class="easyui-textbox" style="width:300px;height:25px" id="nombre" name="nombre" value="">
					</td>
				</tr>
				<tr>
					<td>Fecha:</td>
                    <td>
                    	Desde:
						<input class="easyui-datebox" data-options="formatter:myformatter,parser:myparser,editable:false" style="width:156px;height:25px" id="fecha_desde" name="fecha_desde" value="">
						Hasta:
						<input class="easyui-datebox" data-options="formatter:myformatter,parser:myparser,editable:false" style="width:156px;height:25px" id="fecha_hasta" name="fecha_hasta" value="">
						<a href="javascript:void(0)" class="easyui-linkbutton" onClick="doFilter()">Buscar</a>
					</td>
				</tr>
                <tr>
                    <td>Seleccione:</td>
                    <td>
						<div id="aNotificar" title="Checkbox in DataList" style="border-style: solid;border-color:green;border-radius:5px;width:850px;height:200px;overflow:auto">
							<input type="checkbox" id="seleccion[]" name="seleccion[]" value="" onclick="seleccionar();" disabled="disabled" />Seleccionar Todo
						</div>
					</td>
                </tr>
                <tr>
                    <td>Adjuntar:</td>
                    <td><input class="easyui-filebox" name="adjunto" style="width:850px"></input></td>
                </tr>
            </table>

			<div style="text-align:center;padding:5px">
				<select class="easyui-combobox" name="guardayyenviar" style="width:150px" data-options="panelHeight:'auto',editable:false">
					<!--<option value="off">Guardar sin Enviar</option>-->
					<option value="on">Guardar y Enviar</option>
				</select>
				<a href="javascript:void(0)" class="easyui-linkbutton" onClick="submitForm()">Guardar</a>
				<a href="javascript:void(0)" class="easyui-linkbutton" onClick="returnForm()">Regresar</a>
			</div>
        </form>
        </div>
    </div>
    <script>
        function submitForm(){
			form = document.forms["ff"]; 
			
			if(document.getElementById("notificacion").value == "")
			{
				alert("Falta texto a enviar");
				return false;
			}

			swerror = true;
			for(i=0;i<form.elements.length;i++)
			{ 
				if(form.elements[i].type == "checkbox")
				{
					if(form.elements[i].checked==1 && form.elements[i].value!="on")
					{
						swerror = false;
						break;
					}
				}
			}

			if(swerror)
			{
				alert("Debe seleccionar algún destinatario");
				return false;
			}
		
			document.getElementById("ff").submit();
            //$('#ff').form('submit');
        }
        function clearForm(){
            $('#ff').form('clear');
        }
        function returnForm(){
            window.location="../../notificacioneslist.php";
        }
    </script>


	<script src="editor/parser_rules/advanced.js"></script>
	<script src="editor/dist/wysihtml5-0.3.0.js"></script>	

	<script>
		var editor = new wysihtml5.Editor("notificacion", {
			toolbar:      "toolbar",
			stylesheets:  "editor/css/stylesheet.css",
			parserRules:  wysihtml5ParserRules
		});
  	</script>
	
	<script>
		function doFilter(value){
			$.ajaxSetup({"cache": false});
			var myType = value;

			if(myType == "") {
				alert("Seleccione a quién va a notificar.");
				return false;
			}

			//alert($('#notificar').textbox('getValue'));
			if($('#notificar').textbox('getValue') == "" || $('#notificar').textbox('getValue') == null) {
				alert("Seleccione Notificar a");
				return false;
			}

			var cedula = "";
			var nombre = "";
			var fecha_desde = "";
			var fecha_hasta = "";

			if(myType != "") {
				form = document.forms["ff"]; 
				
				for(i=0;i<form.elements.length;i++)
				{ 
					//alert(form.elements[i].name + " | " + form.elements[i].value + " | " + form.elements[i].type);
					switch(form.elements[i].name) {
					case "notificar":
						myType = form.elements[i].value;
						break;
					case "cedula":
						cedula = form.elements[i].value;
						break;
					case "nombre":
						nombre = form.elements[i].value;
						break;
					case "fecha_desde":
						fecha_desde = form.elements[i].value;
						break;
					case "fecha_hasta":
						fecha_hasta = form.elements[i].value;
						break;
					}

				}
			}

			$.ajax({
				type: "GET",
				url: "filtertype.php?tipo="+myType+"&cedula="+cedula+"&nombre="+nombre+"&fecha_desde="+fecha_desde+"&fecha_hasta="+fecha_hasta
			}).done(function(info){
				$("#aNotificar").html(info);
			})
			//console.log("Y es: "+myType);
		}
		
		function seleccionar(){
			form = document.forms["ff"]; 
			
			for(i=0;i<form.elements.length;i++)
			{ 
				if(form.elements[i].type == "checkbox")
				{
					var chk=form.elements[i].checked;
					break;
				}
			}
			
			for(i=0;i<form.elements.length;i++) 
				if(form.elements[i].type == "checkbox")	
					form.elements[i].checked=chk;
					
		} 			

        function myformatter(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            //return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
			return (d<10?('0'+d):d)+"/"+(m<10?('0'+m):m)+"/"+y;
        }
        function myparser(s){
            if (!s) return new Date();
            var ss = (s.split('/'));
            var y = parseInt(ss[0],10);
            var m = parseInt(ss[1],10);
            var d = parseInt(ss[2],10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                //return new Date(y,m-1,d);
				return new Date(d,m-1,y);
            } else {
                return new Date();
            }
        }
		
	</script>	
</body>
</html>