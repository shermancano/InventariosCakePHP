<script language="Javascript" type="text/javascript" src="/js/responsables/add.js"></script>

<div class="responsables form">
<?php echo $this->Form->create('Responsable');?>
	<fieldset>
		<legend><?php __(utf8_encode('Nuevo Responsable')); ?></legend>
	<?php
		echo $this->Form->input('ceco_id', array('label' => 'Centro de Costo', 'options' => $centros_costos));
		echo $this->Form->input('usua_id', array('type' => 'hidden'));
		echo $this->Form->input('resp_nombre', array('label' => 'Responsable'));
		echo $this->Form->input('esre_id', array('label' => 'Estado', 'options' => $estados));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>
