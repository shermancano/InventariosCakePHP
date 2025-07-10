<script language="Javascript" type="text/javascript" src="/js/rechazos_existencias/index.js"></script>

<div class="rechazos_existencias index">
	<h2><?php __('Rechazos de Existencia');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('Rechazado por', 'Usuario.usua_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Fecha', 'RechazoExistencia.reex_fecha');?></th>
			<th><?php echo $this->Paginator->sort('Desde', 'Existencia.CentroCostoPadre.ceco_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Hacia', 'Existencia.CentroCosto.ceco_nombre');?></th>
			<th>Motivo</th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($rechazos as $rechazo):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $rechazo['Usuario']['usua_nombre']; ?>&nbsp;</td>
		<td><?php echo date("d-m-Y H:i:s", strtotime($rechazo['RechazoExistencia']['reex_fecha'])); ?>&nbsp;</td>
		<td><?php echo $rechazo['Existencia']['CentroCostoPadre']['ceco_nombre']; ?>&nbsp;</td>
		<td><?php echo $rechazo['Existencia']['CentroCosto']['ceco_nombre']; ?>&nbsp;</td>
		<td class="motivo_text"><?php echo substr($rechazo['RechazoExistencia']['reex_motivo'], 0, 20); ?>...<span id="motivo_span_text" style="display:none;"><?php echo nl2br($rechazo['RechazoExistencia']['reex_motivo']); ?></span>&nbsp;</td>
		<td class="actions">
			<?php
				echo $this->Html->link(__('Editar', true), array('action' => 'edit', $rechazo['RechazoExistencia']['reex_id']));
				echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $rechazo['RechazoExistencia']['reex_id']), null, sprintf(__('Esta seguro de eliminar el rechazo ID #%s?', true), $rechazo['RechazoExistencia']['reex_id']));

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
</div>

<?php
	include("views/sidebars/menu.php");
?>