<style>
.label_select::after {
    color: #e32;
    content: "*";
    display: inline;
}
</style>
<script language="Javascript" type="text/javascript" src="/js/activos_fijos/add_traslado.js"></script>

<div class="traslado form">
<?php echo $this->Form->create('TrasladoActivoFijo', array('id' => 'FormTraslado', 'url' => '/activos_fijos/add_traslado'));?>
	<fieldset>
    	<legend><?php __(utf8_encode('Traslados Activos Fijos'));?></legend>
    <?php
		echo $this->Form->input('ceco_id', array('type' => 'hidden', 'value' => $ceco_id));
		echo $this->Form->input('ceco_nombre', array('label' => 'Desde', 'readonly' => 'readonly', 'value' => $ceco_nombre));
		//echo $this->Form->input('ceco_id_hijo', array('label' => 'Hacia', 'options' => $centros_costos));
	?>
    	<label class="required label_select">&nbsp;Hacia</label>
        &nbsp;
    	<select name="data[TrasladoActivoFijo][ceco_id_hijo]">
            <?php					
            	foreach ($centros_costos as $ceco_id => $centroCosto) {
					$style = '';
					if (in_array($ceco_id, $centros_costos_paint)) {
						$style = "style='color: #e32;'";
					}
			?>
            	<option value="<?php echo $ceco_id;?>" <?php echo $style;?>><?php echo $centroCosto;?></option>
            <?php
				}
			?>
        </select> 
    <?php
		echo $this->Form->input('acfi_descripcion', array('type' => 'textarea', 'label' => 'Descripción'));
		echo $this->Form->input('acfi_observaciones', array('type' => 'textarea', 'label' => 'Observaciones'));
	?>    	
    </fieldset>
    <fieldset>
		<legend><?php __('Detalle de Traslado'); ?></legend>
		<p><strong>* Solo se trasladar&aacute;n activos que mantienen stock vigente en el Centro de Costo.</strong></p>
		<br />
		<table width="100%" id="detalle_form" border="0">
			<tr>
				<td width="50%">
					<span class="input select required">
						<label>Ingrese nombre o c&oacute;digo de producto</label>
						<input type="text" id="codigo" />
						<input type="hidden" id="ubaf_codigo" />
					</span>
				</td>
				<td>
					<div class="submit">
						<input id="nuevo_detalle" type="button" value="Agregar" />&nbsp;&nbsp;<img style="display:none;" id="ajax_loader" src="/img/ajax-loader.gif" alt="0"/>
					</div>
				</td>
			</tr>
		</table>
		
		<table width="100%" id="table_detalle" style="display:none;">
			<tr>
				<th width="15%">C&oacute;digo</th>
				<th width="25%">Producto</th>
				<th width="10%">Propiedad</th>
				<th width="10%">Situaci&oacute;n</th>
				<th width="10%">Marca</th>
				<th width="10%">Color</th>		
                <th width="10%">Modelo</th>
                <th width="10%">Serie</th>
                <th width="10%">Depreciable</th>
				<th width="10%">Vida &uacute;til</th>
                <th width="5%">Acciones</th>
                
			</tr>
		</table>		
	</fieldset>
	<div class="submit">
		<input type="button" value="Guardar" id="form_submit" />
	</div>
</form>
</div>

<?php
	include("views/sidebars/menu.php");
?>