<div class="gastos index">
	<h2><?php __('Centros de Salud/Costos');?></h2>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('Nombre', 'ceco_nombre');?></th>
			<th><?php echo $this->Paginator->sort(utf8_encode('Dirección'), 'ceco_direccion');?></th>
			<th><?php echo $this->Paginator->sort('Comuna', 'comu_id');?></th>
			<th><?php echo $this->Paginator->sort('Centro Padre', 'ceco_id_padre');?></th>
			<th><?php echo $this->Paginator->sort(utf8_encode('Es campaña?'), 'ceco_campana');?></th>			
			<th class="actions"><?php __('Acciones');?></th>
		</tr>
	<?php
	$i = 0;
	foreach ($centros_costos as $cc):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
    	<td><?php echo "<a href=\"/usuarios/selCentroCosto/".$cc['CentroCosto']['ceco_id']."\">".$cc['CentroCosto']['ceco_nombre'];?></td>
		<td><?php echo $cc['CentroCosto']['ceco_direccion']; ?>&nbsp;</td>
		<td><?php echo $cc['Comuna']['comu_nombre']; ?>&nbsp;</td>
		<td><?php echo $cc['CentroCosto2']['ceco_nombre']; ?>&nbsp;</td>
		<td>
		<?php
			if ($cc['CentroCosto']['ceco_campana'] == 1) {
				echo "Si";
			} else {
				echo "No";
			}
		?>
		&nbsp;
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Editar', true), array('action' => 'edit', $cc['CentroCosto']['ceco_id'])); ?>
			<?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $cc['CentroCosto']['ceco_id']), null, __('La accion eliminara el Centro de Costo y todos los bienes asociados a el, esta seguro que desea continuar?', true)); ?>
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
</div>

<?php
	include("views/sidebars/menu.php");
?>
