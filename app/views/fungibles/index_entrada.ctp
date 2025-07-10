<div class="entrada index">
	<h2><?php __('Entrada de Fungibles');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th width="4%">&nbsp;</th>
			<th><?php echo $this->Paginator->sort(utf8_encode('Código'));?></th>
            <th><?php echo $this->Paginator->sort('Centro de Costo Padre');?></th>
			<th><?php echo $this->Paginator->sort('Centro de Costo');?></th>
			<th><?php echo $this->Paginator->sort('Proveedor');?></th>
			<th><?php echo $this->Paginator->sort('Fecha');?></th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
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
	<table id="avisos">
		<tr>
			<td width="3%"><img src="/img/test-fail-icon.png" border="0" alt="0"/></td>
			<td>Entradas no recepcionadas.</td>
		</tr>
		<tr>
			<td width="3%"><img src="/img/test-pass-icon.png" border="0" alt="0" /></td>
			<td>Entradas recepcionadas.</td>
		</tr>
		<tr>
			<td width="3%"><img src="/img/icon-yellow.png" border="0" alt="0" /></td>
			<td>Entradas directas.</td>
		</tr>
	</table>
</div>

<?php
	include("views/sidebars/menu.php");
?>