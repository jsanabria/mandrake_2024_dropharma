<?php
session_start();

$id = $_REQUEST["id"]; 
?>
		<div class="container">		
			<div class="panel panel-primary">
				<div class="panel-body">
					
					<form name="form1" id="form1" method="post" action="include/fotos_subir_ne_guarda.php" enctype="multipart/form-data">
						
						<h4 class="text-center">Cargar Multiples Archivos</h4>
						
						<div class="form-group">
							<div class="col-sm-8">
								<label class="col-sm-2 control-label">Archivos</label> 
								<input type="file" class="form-control" id="archivo[]" name="archivo[]" multiple="">
								<input type="hidden" id="id" name="id" value="<?php echo $id; ?>"> 
								<button type="submit" class="btn btn-primary">Cargar</button>
							</div>
						</div>
						
					</form>
					
				</div>
			</div>
		</div>
