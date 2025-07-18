<div class="familias index">
	<h2><?php __('Familias');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('Nombre', 'Familia.fami_nombre');?></th>
            <th><?php echo $this->Paginator->sort('Cuenta Contable', 'CuentaContable.cuco_nombre');?></th>
            <th><?php echo $this->Paginator->sort('Tipo de Familia', 'TipoFamilia.tifa_nombre');?></th>            
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($familias as $familia):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $familia['Familia']['fami_nombre']; ?>&nbsp;</td>
        <td><?php echo $familia['CuentaContable']['cuco_nombre']; ?>&nbsp;</td>
        <td><?php echo $familia['TipoFamilia']['tifa_nombre']; ?>&nbsp;</td>        
		<td class="actions">
			<?php echo $this->Html->link(__('Editar', true), array('action' => 'edit', $familia['Familia']['fami_id'])); ?>
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