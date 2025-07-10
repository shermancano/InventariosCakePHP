<script language="Javascript" type="text/javascript" src="/js/permisos/index.js"></script>

<div class="permisos index">
<?php echo $this->Form->create('Permiso', array('type' => 'file', 'url' => '/permisos/index'));?>
	<h2><?php __('Permisos');?></h2>
 	
	<p><strong>Marque la(s) casilla(s) para hacer disponible la acci&oacute;n del m&oacute;dulo para el perfil mostrado.</strong></p>
	<p><strong>Haga click en el nombre del perfil para mostrar sus acciones.</strong></p>
	<p><strong>Haga click en el nombre del m&oacute;dulo para marcar/desmarcar todas sus acciones.</strong></p>
	<br />
	<?php
		foreach ($info as $key => $inf) {			
			if ($inf['className'] == 'Existencias') {
				unset($info[$key]); // Eliminamos mantenedor de existecias
			}
			
			if ($inf['className'] == 'RechazosExistencias') {
				unset($info[$key]); // Eliminamos mantenedor rechazo de existencia
			}
			
			if ($inf['className'] == 'Solicitudes') {
				unset($info[$key]); // Eliminamos mantenedor de solicitudes
			}
			
			if ($inf['className'] == 'RechazosSolicitudes') {
				unset($info[$key]); // Eliminamos mantenedor rechazo de solicitudes
			}
			
			if ($inf['className'] == 'OrdenesCompras') {
				unset($info[$key]); // Eliminamos mantenedor ordenes de compra
			}
			
			if ($inf['className'] == 'Contratos') {
				unset($info[$key]); // Eliminamos mantenedor contratos
			}
			
			if ($inf['className'] == 'Wizards') {
				unset($info[$key]); // Eliminamos wizards
			}			
			
			// Eliminamos sub-items
			foreach($inf['methods'] as $key2 => $meth) {
				if ($meth == 'existencias') {
					unset($info[$key]['methods'][$key2]); // Eliminamos reporte de existencias
				}
			}
		}

		$i = 0;
		foreach ($perfiles as $perfil) {
			$perf_id = $perfil['Perfil']['perf_id'];
			$perf_nombre = $perfil['Perfil']['perf_nombre'];
			
			echo "<fieldset>\n";
			echo "<legend style='cursor:pointer;'>".$perf_nombre."</legend>\n";
			echo "<table width='100%' border='0' style='display: none;'>\n";
			
			foreach ($info as $class) {
				echo "<tr>\n";
				echo "	<td><p class='controller_name'><strong>".$class['className']."</strong></p>\n";
				echo "       <input type='hidden' name='data[".$i."][perf_id]' value='".$perf_id."' />\n";
				echo "  </td>\n";
				
				foreach ($class['methods'] as $method) {
					echo "	<td>\n";
					echo "    <span class='method_name'>".$method."</span>\n";
					$path = $class['path']."/".$method;
					
					if (in_array($path, $permisos[$perf_id])) {
						echo "<input name='data[".$i."][path][]' value='".$path."' type='checkbox' checked='checked' />\n";
					} else {
						echo "<input name='data[".$i."][path][]' value='".$path."' type='checkbox' />\n";
					}
					
					echo "  </td>\n";
				}
				echo "</tr>\n";
			}
			
			echo "</table>\n";
			echo "</fieldset>\n";
			$i++;
		}
		
	?>
<?php echo $this->Form->end(__('Guardar', true));?>	
</div>

<?php
	include("views/sidebars/menu.php");
?>