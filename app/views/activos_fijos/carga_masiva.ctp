<div class="activos_fijos form">
<?php echo $this->Form->create('CargaMasiva', array('url' => '/activos_fijos/carga_masiva', 'type' => 'file'));?>
	<fieldset>
		<legend><?php __('Carga Masiva'); ?></legend>
		<p><strong>* Para descargar el formato del archivo de carga, click <a href="/files/carga_activo_fijo.xls">ac&aacute;.</a></strong></p>
		<p><strong>* El archivo debe ser CSV delimitado por puntos y comas (;)</strong></p>
		<br />
	<?php
		echo $this->Form->input('archivo', array('type' => 'file', 'label' => 'Archivo'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Subir Archivo', true));?>

	<?php
		if (isset($resp)) :	
	?>
		<br />
		<form>
			<fieldset>
				<legend><?php __('Reporte'); ?></legend>
				<?php
					echo $resp;
				?>
			</fieldset>
		</form>
	<?php
		endif;
	?>

</div>


<?php
	include("views/sidebars/menu.php");
?>