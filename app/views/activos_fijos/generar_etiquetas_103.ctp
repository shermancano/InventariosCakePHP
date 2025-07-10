<script type="text/javascript" src="/js/activos_fijos/codigos_barra.js"></script>
<div class="entradas form">
<?php echo $this->Form->create('ActivoFijo', array('id' => 'FormActivoFijo'));?>
	<fieldset>
		<legend><?php __('Generar Etiquetas Formato 66 x 25'); ?></legend>
        <?php
        	echo $this->Form->input('numero', array('label' => utf8_encode('Imprimir en posición'), 'options' => $numero, 'value' => 1, 'style' => 'width: 10%'));
		?>
   	</fieldset>
    <div class="submit">
		<input type="button" value="Generar" id="form_submit_103" />
	</div>
</form>
</div>
<?php
	include("views/sidebars/menu.php");
?>
