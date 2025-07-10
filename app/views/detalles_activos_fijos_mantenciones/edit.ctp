<script language="Javascript" type="text/javascript" src="/js/mantenciones_activos_fijos/edit.js"></script>
<div class="mantenciones index">
<?php echo $this->Form->create('ActivoFijoMantencion', array('id' => 'MantencionActivoFijo', 'url' => '/detalles_activos_fijos_mantenciones/edit'));?>
<fieldset>
	<legend><?php echo __('Edición de Mantenimiento');?></legend>
	<div class="input select required">
        <label>Ingrese nombre o c&oacute;digo de producto</label>
        <input type="text" class="codigo" value="<?php echo $this->data['UbicacionActivoFijo']['Producto']['prod_nombre'].' ('.$this->data['ActivoFijoMantencion']['ubaf_codigo'].')';?>" />
        <input type="hidden" name="data[ActivoFijoMantencion][ubaf_codigo]" id="MantencionActivoFijoUbafCodigo" value="<?php echo $this->data['ActivoFijoMantencion']['ubaf_codigo'];?>" />
    </div>
	<?php		
        echo $this->Form->input('ceco_id', array('type' => 'hidden'));
        echo $this->Form->input('afma_numero_factura', array('label' => 'Número de Factura', 'type' => 'text', 'style' => 'width:20%'));
        echo $this->Form->input('afma_fecha_factura', array('label' => 'Fecha de Factura', 'type' => 'text', 'style' => 'width:20%'));
        echo $this->Form->input('prov_id', array('label' => 'Proveedor', 'empty' => '-- Seleccione Opción --', 'options' => $proveedores, 'value' => $this->data['ActivoFijoMantencion']['prov_id']));
		echo $this->Form->input('afma_modelo', array('label' => 'Modelo', 'type' => 'text'));
		echo $this->Form->input('afma_marca', array('label' => 'Marca', 'type' => 'text'));
		echo $this->Form->input('afma_ano', array('label' => 'Año', 'type' => 'date', 'dateFormat' => 'Y', 'minYear' => date('Y') -25, 'maxYear' => date('Y') +1));
		echo $this->Form->input('afma_patente', array('label' => 'Patente', 'type' => 'text'));
		echo $this->Form->input('afma_motor', array('label' => 'Motor', 'type' => 'text'));	
		echo $this->Form->input('afma_valor_total', array('type' => 'hidden'));	
		echo $this->Form->input('afma_year', array('type' => 'hidden', 'value' => $year));	
		echo $this->Form->input('afma_id');	
    ?>
</fieldset>
<table width="100%">
	<tr>
    	<td width="95%" style="text-align:right; border-bottom:#FFF;"><a id="add_mantencion"><img src="/img/add.png" title="Presione aquí para agregar mas mantenciones" alt="" /></a></td>
        <td style="border-bottom:#FFF;"><a id="del_mantencion"><img src="/img/delete.png" title="Presione aquí para eliminar última fila" alt="" /></a></td>
    </tr>
</table>
<fieldset>
	<legend>
		<?php echo __('Editar Detalle de Mantención');?>    	
    </legend>
    <table width="100%" id="tabla_mantencion">
    	<tr>
            <th>Fecha Servicio</th>
            <th>Kilometraje</th>
            <th>Trabajo y/o Servicio</th>
            <th>Operador</th>
            <th>Costo</th>
            <th>Observación</th>
        </tr>
        <?php
        	$indice = 0;
			foreach ($findDetalle as $row) {
		?>
        <tr class="tr_mantencion">        	
            <td><?php echo $this->Form->input('DetalleMantencion.'.$indice.'.dema_fecha_servicio', array('label' => false, 'type' => 'text', 'style' => 'width:100%', 'class' => 'fecha_servicio', 'value' => date('d-m-Y', strtotime($row['DetalleActivoFijoMantencion']['dema_fecha_servicio']))));?></td>
            <td><?php echo $this->Form->input('DetalleMantencion.'.$indice.'.dema_kilometraje', array('label' => false, 'type' => 'text', 'style' => 'width:100%', 'onKeyPress' => 'return validchars(event, num)', 'value' => $row['DetalleActivoFijoMantencion']['dema_kilometraje'], 'class' => 'kilometraje'));?></td>
            <td><?php echo $this->Form->input('DetalleMantencion.'.$indice.'.dema_descripcion', array('label' => false, 'type' => 'textarea', 'rows' => 2, 'style' => 'width:100%', 'value' => $row['DetalleActivoFijoMantencion']['dema_descripcion'], 'class' => 'descripcion'));?></td>
            <td><?php echo $this->Form->input('DetalleMantencion.'.$indice.'.dema_nombre_operador', array('label' => false, 'type' => 'textarea', 'rows' => 2, 'style' => 'width:100%', 'value' => $row['DetalleActivoFijoMantencion']['dema_nombre_operador'], 'class' => 'operador'));?></td>
            <td><?php echo $this->Form->input('DetalleMantencion.'.$indice.'.dema_valor', array('label' => false, 'type' => 'text', 'style' => 'width:100%', 'onKeyPress' => 'return validchars(event, num)', 'class' => 'valor', 'value' => $row['DetalleActivoFijoMantencion']['dema_valor']));?></td>
            <td><?php echo $this->Form->input('DetalleMantencion.'.$indice.'.dema_observacion', array('label' => false, 'type' => 'textarea', 'rows' => 2, 'style' => 'width:100%', 'value' => $row['DetalleActivoFijoMantencion']['dema_observacion']));?></td>         
        </tr>
        <?php
				$indice++;
        	}
		?>
    </table>
    <table width="100%">
    	<tr>
        	<td width="90%" style="text-align:right; border-bottom:#FFF;"><strong>Valor Total:</strong></td>
            <td style="border-bottom:#FFF;" id="valor_total">$ <?php echo $this->data['ActivoFijoMantencion']['afma_valor_total'];?></td>
        </tr>
    </table>
</fieldset>
<?php echo $this->Form->end(__('Guardar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>