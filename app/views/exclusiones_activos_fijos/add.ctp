<script type="text/javascript" src="/js/activos_fijos/codigos_barra.js"></script>
<div class="codigos_barra index">
	<?php echo $this->Form->create('DetalleExcluido', array('id' => 'DetalleExcluido', 'style' => 'width:100%', 'url' => '/exclusiones_activos_fijos/add'));?>
	<div class="cod_barra_tit">
		<span id="tit"><?php __('Nueva Exclusión Activo Fijo');?></span>
		<span id="exportar">
        	<input type="button" value="Excluir Productos Marcados" id="excluidos" />			
		</span>
	</div>
	<?php
		echo $this->Form->input('ExclusionActivoFijo.ceco_id', array('type' => 'hidden'));
        echo $this->Form->input('ExclusionActivoFijo.devi_id', array('type' => 'hidden'));
		echo $this->Form->input('ExclusionActivoFijo.moba_id', array('type' => 'hidden'));
		echo $this->Form->input('ExclusionActivoFijo.exaf_observacion', array('type' => 'hidden'));
		echo $this->Form->input('ExclusionActivoFijo.exaf_numero_documento', array('type' => 'hidden'));
    ?>
    <br /><br />
	
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('Código', 'UbicacionActivoFijo.ubaf_codigo');?></th>
            <th><?php echo $this->Paginator->sort('Producto', 'Producto.prod_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Centro de Costo', 'CentroCosto.ceco_nombre');?></th>			
            <th style="width:15%"><?php echo '<a id="casilla">¿Dar de Baja?</a>';?></th>
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
		<td><?php echo $deta['DetalleBaja']['deba_codigo']; ?>&nbsp;</td>
        <td width="30%"><?php echo $deta['Producto']['prod_nombre']; ?>&nbsp;</td>
		<td><?php echo $centros_costos[$deta['BajaActivoFijo']['ceco_id']]; ?>&nbsp;</td>		
        <td>
        	<?php
				echo $this->Form->input('DetalleExclusion.'.$indice.'.dete_codigo', array('type' => 'hidden', 'value' => $deta['DetalleBaja']['deba_codigo']));
				echo $this->Form->input('DetalleExclusion.'.$indice.'.prod_id', array('type' => 'hidden', 'value' => $deta['DetalleBaja']['prod_id']));
				echo $this->Form->input('DetalleExclusion.'.$indice.'.prop_id', array('type' => 'hidden', 'value' => $deta['DetalleBaja']['prop_id']));
				echo $this->Form->input('DetalleExclusion.'.$indice.'.situ_id', array('type' => 'hidden', 'value' => $deta['DetalleBaja']['situ_id']));
				echo $this->Form->input('DetalleExclusion.'.$indice.'.marc_id', array('type' => 'hidden', 'value' => $deta['DetalleBaja']['marc_id']));
				echo $this->Form->input('DetalleExclusion.'.$indice.'.colo_id', array('type' => 'hidden', 'value' => $deta['DetalleBaja']['colo_id']));
				echo $this->Form->input('DetalleExclusion.'.$indice.'.mode_id', array('type' => 'hidden', 'value' => $deta['DetalleBaja']['mode_id']));
				echo $this->Form->input('DetalleExclusion.'.$indice.'.dete_fecha_garantia', array('type' => 'hidden', 'value' => $deta['DetalleBaja']['deba_fecha_garantia']));
				echo $this->Form->input('DetalleExclusion.'.$indice.'.dete_depreciable', array('type' => 'hidden', 'value' => $deta['DetalleBaja']['deba_depreciable']));
				echo $this->Form->input('DetalleExclusion.'.$indice.'.dete_vida_util', array('type' => 'hidden', 'value' => $deta['DetalleBaja']['deba_vida_util']));
				echo $this->Form->input('DetalleExclusion.'.$indice.'.dete_depreciable', array('type' => 'hidden', 'value' => $deta['DetalleBaja']['deba_depreciable']));
				echo $this->Form->input('DetalleExclusion.'.$indice.'.dete_fecha_adquisicion', array('type' => 'hidden', 'value' => $deta['DetalleBaja']['deba_fecha_adquisicion']));
				echo $this->Form->input('DetalleExclusion.'.$indice.'.dete_precio', array('type' => 'hidden', 'value' => $deta['DetalleBaja']['deba_precio']));
				echo $this->Form->input('DetalleExclusion.'.$indice.'.dete_serie', array('type' => 'hidden', 'value' => $deta['DetalleBaja']['deba_serie']));				
            	echo $this->Form->input('DetalleExclusion.'.$indice.'.dete_check', array('label' => false, 'type' => 'checkbox'));
			?>
        </td>
		<td class="actions">			
            <?php
				if (trim($deta['DetalleBaja']['deba_fecha_garantia']) == "") {
					$ubaf_fecha_garantia = null;
				} else {
					$ubaf_fecha_garantia = date("d-m-Y", strtotime($deta['DetalleBaja']['deba_fecha_garantia']));
				}
				
				$rel  = $deta['DetalleBaja']['deba_codigo']."|".$deta['Producto']['prod_nombre']."|".$centros_costos[$deta['BajaActivoFijo']['ceco_id']]."|";
				$rel .= $deta['Propiedad']['prop_nombre']."|".$deta['Situacion']['situ_nombre']."|".$deta['Marca']['marc_nombre']."|";
				$rel .= $deta['Color']['colo_nombre']."|".$deta['Modelo']['mode_nombre']."|".$deta['DetalleBaja']['deba_serie']."|";
				$rel .= date("d-m-Y", strtotime($deta['DetalleBaja']['deba_fecha_adquisicion']))."|".$ubaf_fecha_garantia."|".$deta['DetalleBaja']['deba_valor_baja']."|";
				$rel .= $deta['DetalleBaja']['deba_depreciable']."|".$deta['DetalleBaja']['deba_vida_util'];
				
				echo $this->Html->link(__('Ver Detalle', true), 'javascript:;', array('class' => 'ver_detalle_af', 'rel' => $rel));
			?>
            
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
	'format' => __(utf8_encode('Pagina %page% de %pages%, mostrando %current% registros de un total de %count% total, empezando en %start%, terminando en %end%'), true)
	));
	?>
	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('anterior', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('siguiente', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
    <?php echo $this->Form->end(__('', true));?>
</div>
<div id="bajas_dialog" style="display:none;">
    <form id="ExcluidoActivoFijo" name="ExcluidoActivoFijo" method="post" style="width:100%">
        <br />
        <table width="100%" id="tabla_bajas">
        	<tr>
                <td width="30%"><strong>Destino</strong></td>
                <td><?php echo $this->Form->input('Excluido.ceco_id', array('label' => false, 'empty' => '-- Seleccione Opción --', 'options' => $bodegas));?></td>
            </tr>
            <tr>
                <td><strong>Tipo de Baja</strong></td>
                <td><?php echo $this->Form->input('Excluido.devi_id', array('label' => false, 'empty' => '-- Seleccione Opción --', 'options' => $dependencias));?></td>
            </tr>
            <tr>
                <td><strong>Motivo</strong></td>
                <td><?php echo $this->Form->input('Excluido.moba_id', array('label' => false, 'empty' => '-- Seleccione Opción --', 'options' => $motivos));?></td>
            </tr>
            <tr>
                <td><strong>N&deg; de Resoluci&oacute;n</strong></td>
                <td><?php echo $this->Form->input('Excluido.exaf_numero_documento', array('label' => false, 'type' => 'text', 'style' => 'width:72%'));?></td>
            </tr>
            <tr>
                <td><strong>Observaci&oacute;n</strong></td>
                <td><?php echo $this->Form->input('Excluido.exaf_observacion', array('label' => false, 'type' => 'textarea', 'style' => 'width:95%', 'rows' => 3, 'maxLength' => 150));?></td>
            </tr>
        </table>
        <table width="100%" id="tabla_bajas_footer">
            <tr>
                <td width="80%" style="text-align:right;"><input type="button" value="Guardar" id="guardar_excluidos" /></td>
                <td style="text-align:right;"><input type="button" value="Cancelar" id="cancelar_bajas" /></td>
            </tr>
        </table>
    </form>
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
			<td><?php echo utf8_encode("¿Es Depreciable?"); ?></td>
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