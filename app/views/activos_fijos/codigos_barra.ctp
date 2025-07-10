<script type="text/javascript" src="/js/activos_fijos/codigos_barra.js"></script>
<div class="codigos_barra index">	
	<div class="cod_barra_tit">
		<span id="tit"><?php __(utf8_encode('C�digos de Barra'));?></span>
		<span id="exportar">        	
			<input type="button" value="Exportar a Formato 66 x 25" id="exportar_codigos_103"/>
			<input type="button" value="Exportar c&oacute;digos a PDF" id="exportar_codigos" />
		</span>
	</div>
	<?php
        echo $this->Form->input('BajaActivoFijo.devi_id', array('type' => 'hidden'));
		echo $this->Form->input('BajaActivoFijo.moba_id', array('type' => 'hidden'));
		echo $this->Form->input('BajaActivoFijo.baaf_observacion', array('type' => 'hidden'));
		echo $this->Form->input('BajaActivoFijo.baaf_numero_documento', array('type' => 'hidden'));
    ?>
    <br />
	
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort(utf8_encode('C�digo'), 'UbicacionActivoFijo.ubaf_codigo');?></th>
            <th><?php echo $this->Paginator->sort('Producto', 'Producto.prod_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Centro de Costo', 'CentroCosto.ceco_nombre');?></th>
			<th><?php echo $this->Paginator->sort(utf8_encode('�Es Depreciable?'), 'DetalleActivoFijo.deaf_depreciable');?></th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	$indice = 0;
	foreach ($detalles as $deta):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $deta['UbicacionActivoFijo']['ubaf_codigo']; ?>&nbsp;</td>
        <td width="30%"><?php echo $deta['Producto']['prod_nombre']; ?>&nbsp;</td>
		<td><?php echo $deta['CentroCosto']['ceco_nombre']; ?>&nbsp;</td>
		<td>
			<?php
				if ($deta['UbicacionActivoFijo']['ubaf_depreciable'] == 1) {
					echo "Si";
				} else {
					echo "No";
				}
			?>
			&nbsp;
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__(utf8_encode('Generar C�digo'), true), array('action' => 'genera_codigo_barra', $deta['UbicacionActivoFijo']['ubaf_codigo']), array('target' => '_blank'), null); ?>
            <?php
				if (trim($deta['UbicacionActivoFijo']['ubaf_fecha_garantia']) == "") {
					$ubaf_fecha_garantia = null;
				} else {
					$ubaf_fecha_garantia = date("d-m-Y", strtotime($deta['UbicacionActivoFijo']['ubaf_fecha_garantia']));
				}
				
				$rel  = $deta['UbicacionActivoFijo']['ubaf_codigo']."|".$deta['Producto']['prod_nombre']."|".$deta['CentroCosto']['ceco_nombre']."|";
				$rel .= $deta['Propiedad']['prop_nombre']."|".$deta['Situacion']['situ_nombre']."|".$deta['Marca']['marc_nombre']."|";
				$rel .= $deta['Color']['colo_nombre']."|".$deta['Modelo']['mode_nombre']."|".$deta['UbicacionActivoFijo']['ubaf_serie']."|";
				$rel .= date("d-m-Y", strtotime($deta['UbicacionActivoFijo']['ubaf_fecha_adquisicion']))."|".$ubaf_fecha_garantia."|".$deta['UbicacionActivoFijo']['ubaf_precio']."|";
				$rel .= $deta['UbicacionActivoFijo']['ubaf_depreciable']."|".$deta['UbicacionActivoFijo']['ubaf_vida_util'];
				
				echo $this->Html->link(__('Ver Detalle', true), 'javascript:;', array('class' => 'ver_detalle_af', 'rel' => $rel));
			?>
            <?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete_codigo_barra', $deta['UbicacionActivoFijo']['ubaf_codigo']), array('title' => 'Eliminar el codigo de barra'), __('La accion eliminara el codigo de barra seleccionado y enviara un email al administrador del sistema para dejar constancia del hecho,\nEsta seguro que desea continuar?', true)); ?>
            <div class="params" style="display:none">
            	<?php echo ""; ?>
            </div>	
		</td>
	</tr>
<?php
	$indice++; 
	endforeach; 
?>
	</table>
	
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __(utf8_encode('P�gina %page% de %pages%, mostrando %current% registros de un total de %count% total, empezando en %start%, terminando en %end%'), true)
	));
	?>
	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('anterior', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('siguiente', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div id="detalles_dialog">
	<table width="100%">
		<tr>
			<td>C&oacute;digo</td>
			<td id="ubaf_codigo"></td>
		</tr>
		<tr>
			<td>Producto</td>
			<td id="prod_nombre"></td>
		</tr>
		<tr>
			<td>Centro de Costo</td>
			<td id="ceco_nombre"></td>
		</tr>
		<tr>
			<td>Propiedad</td>
			<td id="prop_nombre"></td>
		</tr>
		<tr>
			<td>Situaci&oacute;n</td>
			<td id="situ_nombre"></td>
		</tr>
		<tr>
			<td>Marca</td>
			<td id="marc_nombre"></td>
		</tr>
		<tr>
			<td>Color</td>
			<td id="colo_nombre"></td>
		</tr>
		<tr>
			<td>Modelo</td>
			<td id="mode_nombre"></td>
		</tr>
		<tr>
			<td>Serie</td>
			<td id="ubaf_serie"></td>
		</tr>
		<tr>
			<td>Fecha de Adquisici&oacute;n</td>
			<td id="ubaf_fecha_adquisicion"></td>
		</tr>
		<tr>
			<td>Fecha de Garant&iacute;a</td>
			<td id="ubaf_fecha_garantia"></td>
		</tr>
		<tr>
			<td>Precio</td>
			<td id="ubaf_precio"></td>
		</tr>
		<tr>
			<td><?php echo utf8_encode("�Es Depreciable?"); ?></td>
			<td id="ubaf_depreciable"></td>
		</tr>
		<tr>
			<td>Vida &Uacute;til</td>
			<td id="ubaf_vida_util"></td>
		</tr>
	</table>
</div>

<?php
	include("views/sidebars/menu.php");
?>
