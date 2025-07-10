<script language="Javascript" type="text/javascript" src="/js/rechazos_activos_fijos/index.js"></script>

<div class="rechazos_existencias index">
	<h2><?php __('Rechazos de Activo Fijo');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('Rechazado por', 'Usuario.usua_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Fecha', 'RechazoActivofijo.reex_fecha');?></th>
			<th><?php echo $this->Paginator->sort('Desde', 'ActivoFijo.CentroCostoPadre.ceco_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Hacia', 'ActivoFijo.CentroCosto.ceco_nombre');?></th>
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
		<td><?php echo date("d-m-Y H:i:s", strtotime($rechazo['RechazoActivoFijo']['reaf_fecha'])); ?>&nbsp;</td>
		<td><?php echo $rechazo['ActivoFijo']['CentroCostoPadre']['ceco_nombre']; ?>&nbsp;</td>
		<td><?php echo $rechazo['ActivoFijo']['CentroCosto']['ceco_nombre']; ?>&nbsp;</td>
		<td class="motivo_text"><?php echo substr($rechazo['RechazoActivoFijo']['reaf_motivo'], 0, 20); ?>...<span id="motivo_span_text" style="display:none;"><?php echo nl2br($rechazo['RechazoActivoFijo']['reaf_motivo']); ?></span>&nbsp;</td>
		<td class="actions">
			<?php
				echo $this->Html->link(__('Editar', true), array('action' => 'edit', $rechazo['RechazoActivoFijo']['reaf_id']));
				echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $rechazo['RechazoActivoFijo']['reaf_id']), null, sprintf(__('Esta seguro de eliminar el rechazo ID #%s?', true), $rechazo['RechazoActivoFijo']['reaf_id']));

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