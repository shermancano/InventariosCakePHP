<div class="tipoContratos index">
	<h2><?php __('Perfiles');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('Perfil', 'perf_nombre');?></th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($perfiles as $perfil):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $perfil['Perfil']['perf_nombre']; ?>&nbsp;</td>
		<td class="actions">
			<?php
				echo $this->Html->link(__('Editar', true), array('action' => 'edit', $perfil['Perfil']['perf_id']));
				echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $perfil['Perfil']['perf_id']), null, sprintf(__('Esta seguro de eliminar el perfil ID #%s?, la accion eliminara tambien todas las acciones asociadas a este perfil, esta seguro que desea continuar?', true), $perfil['Perfil']['perf_id']));

			?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __(utf8_encode('P�gina %page% de %pages%, mostrando %current% registros de un total de %count% total, empezando en %start%, terminando en %end%'), true)
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