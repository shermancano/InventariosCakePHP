<div class="traslados view" id="traslados_view">
<h2><?php  __('Traslado Activo Fijo');?></h2>
<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('ID'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['ActivoFijo']['acfi_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Correlativo'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo sprintf("%012d", $entrada['ActivoFijo']['acfi_correlativo']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Centro de Costo Desde'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['CentroCosto']['ceco_nombre']; ?>
			&nbsp;
		</dd>
        	<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Ubicación Desde'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo utf8_encode($ubicacion); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Centro de Costo Hacia'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['CentroCostoHijo']['ceco_nombre']; ?>
			&nbsp;
		</dd>
        	<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Ubicación Hacia'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo utf8_encode($ubicacionPadre); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Fecha'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo date("d-m-Y H:i:s", strtotime($entrada['ActivoFijo']['acfi_fecha'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __(utf8_encode('Descripci&oacute;n')); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['ActivoFijo']['acfi_descripcion']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Observaciones'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['ActivoFijo']['acfi_observaciones']; ?>
			&nbsp;
		</dd>
	</dl>	
	<br />
	<br />
<h2><?php  __('Detalle');?></h2>
			<table width="100%">
				<tr>
					<th width="30%">Producto</th>
					<th width="10%">Marca</th>
					<th width="10%">Propiedad</th>
					<th width="10%">Color</th>
                    <th width="10%">Modelo</th>
                    <th width="10%">Serie</th>
					<th width="10%">Situaci&oacute;n</th>
					<th width="10%">Precio</th>
                    <th width="10%">Â¿Es Depreciable?</th>
                    <th width="10%">Vida &Uacute;til</th>
                    <th width="10%">Cantidad</th>
				</tr>
				<?php
					$sum_total_neto = 0;
					foreach ($detalles as $deaf) {
				?>
					<tr>
						<td><?php echo $deaf['Producto']['prod_nombre']; ?></td>
                        <td><?php
                        		if (isset($deaf['Marca']['marc_nombre'])){
									echo $deaf['Marca']['marc_nombre'];
								}
									echo $deaf['Marca']['marc_nombre'] = null;
							 ?>
                        </td>
                        <td><?php echo $deaf['Propiedad']['prop_nombre']; ?></td>
                        <td><?php echo $deaf['Color']['colo_nombre']; ?></td>
                        <td><?php 
								if (isset($deaf['Modelo']['mode_nombre'])) {
									echo $deaf['Modelo']['mode_nombre'];								
								}
									echo $deaf['Modelo']['mode_nombre'] = null;
							?>
                        </td>
                        <td><?php
                        		if(isset($deaf['DetalleActivoFijo']['deaf_serie'])) {
									echo $deaf['DetalleActivoFijo']['deaf_serie'];
								}
									$deaf['DetalleActivoFijo']['deaf_serie'] = null;
							?>
                        </td>
                        <td><?php echo $deaf['Situacion']['situ_nombre']; ?></td>
						<td><?php echo $deaf['DetalleActivoFijo']['deaf_precio']; ?></td>
						<td><?php
								if ($deaf['DetalleActivoFijo']['deaf_depreciable'] == 1) {
									echo "Si";
								} else {
									echo "No";
								}
							?></td>
                        <td><?php echo $deaf['DetalleActivoFijo']['deaf_vida_util']; ?></td>
                        <td><?php echo "1"; ?></td>
					</tr>
				<?php
						$sum_total_neto += ($deaf['DetalleActivoFijo']['deaf_precio']+$total);
					}
				?>
			</table>
            <p>
			<?php
			echo $this->Paginator->counter(array(
			'format' => __(utf8_encode('P&aacute;gina %page% de %pages%, mostrando %current% registros de un total de %count% total, empezando en %start%, terminando en %end%'), true)
			));
			?>	</p>

			<div class="paging">
				<?php echo $this->Paginator->prev('<< ' . __('anterior', true), array(), null, array('class'=>'disabled'));?>
			 | 	<?php echo $this->Paginator->numbers();?>
		 |
				<?php echo $this->Paginator->next(__('siguiente', true) . ' >>', array(), null, array('class' => 'disabled'));?>
			</div>
			
			<br />
			<br />
			
			<table width="30%" border="0" id="table_resumen_cal">
				<tr>
					<th>Total Neto</th>
					<th align="right" id="td_valor_neto"><p>$<?php echo number_format($total, 0, ",", "."); ?></p></th>
				</tr>
				<tr>
					<th>IVA (<?php echo $valor_iva;?>%)</th>
					<th id="td_iva" align="right"><p>$<?php echo number_format(round(($total * $valor_iva) / 100), 0, ",", ".");?></p></th>
				</tr>
				<tr>
					<th>Total</th>
					<th id="td_total" align="right">
						<?php
                        				$total_monto = round($total + ($total * $valor_iva / 100));
						?>
						<p>$<?php echo number_format(round($total_monto, 0), 0, ",", "."); ?></p>
					</th>
				</tr>
			</table>
</div>
<?php
	include("views/sidebars/menu.php");
?>
