<div class="entradas view">
<h2><?php  __('Entrada de Activos Fijos');?></h2>
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
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Centro de Costo'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['CentroCosto']['ceco_nombre']; ?>
			&nbsp;
		</dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Ubicación Centro de Costo Registro de Entrada'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo utf8_encode($ubicacionEntrada);?>
			&nbsp;
		</dd>
        <?php
        	if (!empty($entrada['ActivoFijo']['ceco_id_padre'])) {
		?>
            <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Centro de Costo Padre'); ?></dt>
            <dd<?php if ($i++ % 2 == 0) echo $class;?>>
                <?php echo $entrada['CentroCostoPadre']['ceco_nombre']; ?>
                &nbsp;
            </dd>
            <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Ubicación Centro Costo Padre'); ?></dt>
            <dd<?php if ($i++ % 2 == 0) echo $class;?>>
                <?php echo utf8_encode($ubicacionPadre); ?>
                &nbsp;
            </dd>
        <?php
        	}
		?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Proveedor'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['Proveedor']['prov_nombre']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Financiamiento'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['Financiamiento']['fina_nombre']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Fecha'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo date("d-m-Y H:i:s", strtotime($entrada['ActivoFijo']['acfi_fecha'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Orden de Compra'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['ActivoFijo']['acfi_orden_compra']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Tipo de Documento'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['TipoDocumento']['tido_descripcion']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Número de Documento'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['ActivoFijo']['acfi_nro_documento']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Fecha de Documento'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php
				if (trim($entrada['ActivoFijo']['acfi_fecha_documento']) != "") {
					echo date("d-m-Y", strtotime($entrada['ActivoFijo']['acfi_fecha_documento']));
				}
			?>
			&nbsp;
		</dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Tipo Resolución'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['TipoResolucion']['tire_nombre']; ?>
			&nbsp;
		</dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Número Resolución'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['ActivoFijo']['acfi_numero_resolucion']; ?>
			&nbsp;
		</dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Fecha Resolución'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php
				if (trim($entrada['ActivoFijo']['acfi_fecha_resolucion']) != "") {
					echo date('d-m-Y',strtotime($entrada['ActivoFijo']['acfi_fecha_resolucion']));
				}
			?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Descripción'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['ActivoFijo']['acfi_descripcion']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Observaciones'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['ActivoFijo']['acfi_observaciones']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Descargar Comprobante Entrada'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<a href="/activos_fijos/comprobante_entrada/<?php echo $id;?>">Descargar</a>
		</dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Fotografía Adjunta'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php
            	if (!empty($entrada['ActivoFijoDocumento']['acfd_id'])) {
					echo '<a href="/activos_fijos/download_fotografia/'.$entrada['ActivoFijoDocumento']['acfd_id'].'" target="_blank">Descargar</a>';
				} else {
					echo 'Sin fotografia';
				}
			?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Ubicación del Bien'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo utf8_encode($ubicacionBien);?>
			&nbsp;
		</dd>		
	</dl>
	
	<br />
	<br />
	<h2><?php  __('Detalle');?></h2>
	
			<table width="100%">
				<tr>
					<th width="20%">C&oacute;digo</th>
					<th width="35%">Producto</th>
					<th width="12%">Precio Unitario</th>
					<th width="12%"><?php echo utf8_encode("¿Es depreciable?"); ?></th>
				</tr>
				<?php
					foreach ($detalles as $det) {
				?>
					<tr>
						<td><?php echo $det['DetalleActivoFijo']['deaf_codigo']; ?></td>
						<td><?php echo $det['Producto']['prod_nombre']; ?></td>
						<td><?php echo $det['DetalleActivoFijo']['deaf_precio']; ?></td>
						<td>
							<?php
								if ($det['DetalleActivoFijo']['deaf_depreciable'] == 1) {
									echo "Si";
								} else {
									echo "No";
								}
							?>
						</td>
					</tr>
				<?php
					}
				?>
			</table>
</div>
<?php
	include("views/sidebars/menu.php");
?>

