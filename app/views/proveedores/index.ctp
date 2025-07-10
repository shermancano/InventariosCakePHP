<script type="text/javascript" src="/js/proveedores/index.js"></script>
<div class="proveedores index">
	<h2><?php __('Proveedores');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('Nombre', 'prov_nombre');?></th>
			<th><?php echo $this->Paginator->sort('RUT', 'prov_rut');?></th>
			<th><?php echo $this->Paginator->sort('Contacto', 'prov_contacto');?></th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($proveedores as $proveedore):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $proveedore['Proveedor']['prov_nombre']; ?>&nbsp;</td>
		<td><?php echo $proveedore['Proveedor']['prov_rut']; ?>&nbsp;</td>
		<td><?php echo $proveedore['Proveedor']['prov_contacto']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Ver', true), array('action' => 'view', $proveedore['Proveedor']['prov_id'])); ?>
			<?php echo $this->Html->link(__('Editar', true), array('action' => 'edit', $proveedore['Proveedor']['prov_id'])); ?>
			<?php
				//echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $proveedore['Proveedor']['prov_id']), null, sprintf(__('Esta seguro de eliminar al proveedor ID # %s?, la accion eliminara todos los contratos pertenecientes a este proveedor, esta seguro de continuar?', true), $proveedore['Proveedor']['prov_id']));
			?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __(utf8_encode('Página %page% de %pages%, mostrando %current% registros de un total de %count% total, empezando en %start%, terminando en %end%'), true)
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
	<fieldset>
    	<legend>B&uacute;squeda</legend>
        <table width="100%" border="0" class="detalle_form">
            <tbody>
                <tr>
                    <td width="58%">
                        <span class="input select required">
                            <label>Ingrese b&uacute;squeda</label>
                            <input type="text" style="width:456px;" id="busqueda_producto" />								
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </fieldset>
</div>

<?php
	include("views/sidebars/menu.php");
?>