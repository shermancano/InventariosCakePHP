<div class="solicitudes view">
<h2><?php  __('Solicitud');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Desde'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $solicitud['CentroCosto']['ceco_nombre']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Hacia'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $solicitud['CentroCosto2']['ceco_nombre']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Proveedor'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $solicitud['Proveedor']['prov_nombre']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Fecha'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo date("d-m-Y H:i:s", strtotime($solicitud['Solicitud']['soli_fecha'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Tipo de Solicitud'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $solicitud['TipoSolicitud']['tiso_nombre']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Observaciones'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $solicitud['Solicitud']['soli_comentario']; ?>
			&nbsp;
		</dd>
	</dl>
	
	<br />
	<br />
	<h2><?php  __('Detalle');?></h2>
	
		<table width="100%">
			<tr>
				<th width="70%">Producto</th>
				<th>Tipo de Bien</th>
				<th>Cantidad</th>
			</tr>
			<?php
				foreach ($deso_info as $deso) {
			?>
				<tr>
					<td><?php echo $deso['Producto']['prod_nombre']; ?></td>
					<td><?php echo $deso['Producto']['TipoBien']['tibi_nombre']; ?></td>
					<td><?php echo $deso['DetalleSolicitud']['deso_cantidad']; ?></td>
				</tr>
			<?php
				}
			?>
		</table>
		<br />
</div>
<?php
	include("views/sidebars/menu.php");
?>