<script language="Javascript" type="text/javascript" src="/js/encargado_establecimiento/edit.js"></script>

<div class="responsables form">
<?php echo $this->Form->create('EncargadoEstablecimiento', array('url' => '/encargado_establecimientos/edit/'.$this->data['EncargadoEstablecimiento']['enes_id']));?>
	<fieldset>
		<legend><?php __(utf8_encode('Editar Director Establecimiento')); ?></legend>
	<?php
		echo $this->Form->input('enes_id', array('type' => 'hidden'));
		echo $this->Form->input('ceco_id', array('label' => 'Centro de Costo', 'options' => $centros_costos));
		echo $this->Form->input('usua_id', array('type' => 'hidden'));
		echo $this->Form->input('resp_nombre', array('label' => 'Responsable', 'value' => $this->data['Usuario']['usua_nombre']));
		echo $this->Form->input('esre_id', array('label' => 'Estado', 'options' => $estados));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>
