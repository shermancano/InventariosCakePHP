<script language="Javascript" type="text/javascript" src="/js/contratos/index.js"></script>

<div class="contratos index">
	<h2><?php __('Contratos');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo utf8_encode('Nº'); ?></th>
			<th><?php echo $this->Paginator->sort(utf8_encode('Nº Licitación'), 'cont_nro_licitacion');?></th>
			<th><?php echo $this->Paginator->sort('Nombre', 'cont_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Proveedor', 'Proveedor.prov_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Monto de Compra', 'cont_monto_compra');?></th>
			<th><?php echo $this->Paginator->sort('Monto Mensual', 'cont_monto_mensual');?></th>
			<th><?php echo $this->Paginator->sort('Tipo de Monto', 'TipoMonto.timo_descripcion');?></th>
			<th><?php echo $this->Paginator->sort(utf8_encode('Tipo de Renovación'), 'TipoRenovacion.tire_descripcion');?></th>
			<th><?php echo $this->Paginator->sort('Fecha de Inicio', 'cont_fecha_inicio');?></th>
			<th><?php echo $this->Paginator->sort('Fecha de Termino', 'cont_fecha_termino');?></th>
			<th>Vigente</th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	
	foreach ($contratos as $contrato):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $i; ?></td>
		<td><?php echo $contrato['Contrato']['cont_nro_licitacion']; ?>&nbsp;</td>
		<td><?php echo $contrato['Contrato']['cont_nombre']; ?>&nbsp;</td>
		<td><?php echo $contrato['Proveedor']['prov_nombre']; ?>&nbsp;</td>
		<td>
			<?php
				if (preg_match("/\./", $contrato['Contrato']['cont_monto_compra'])) {
					echo number_format($contrato['Contrato']['cont_monto_compra'], 3, ",", ".");
				} else {
					echo number_format($contrato['Contrato']['cont_monto_compra'], 0, "", ".");
				}
			?>
			&nbsp;
		</td>
		<td>
			<?php
				if (trim($contrato['Contrato']['cont_monto_mensual']) != "") {
					if (preg_match("/\./", $contrato['Contrato']['cont_monto_mensual'])) {
						echo $contrato['Contrato']['cont_monto_mensual'];
					} else {
						echo number_format($contrato['Contrato']['cont_monto_mensual'], 0, "", ".");
					}
				}
			?>
			&nbsp;
		</td>
		<td><?php echo $contrato['TipoMonto']['timo_descripcion']; ?>&nbsp;</td>
		<td><?php echo $contrato['TipoRenovacion']['tire_descripcion']; ?>&nbsp;</td>
		<td><?php echo date("d-m-Y", strtotime($contrato['Contrato']['cont_fecha_inicio'])); ?>&nbsp;</td>
		<td><?php echo date("d-m-Y", strtotime($contrato['Contrato']['cont_fecha_termino'])); ?>&nbsp;</td>
		<td>
			<?php
				if ($contrato['Contrato']['cont_vigente'] == "" || $contrato['Contrato']['cont_vigente'] == 1) {
					echo "SI";
				} else {
					echo "NO";
				}
			?>
			&nbsp;
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Ver', true), array('action' => 'view', $contrato['Contrato']['cont_id'])); ?>
			<?php echo $this->Html->link(__('Editar', true), array('action' => 'edit', $contrato['Contrato']['cont_id'])); ?>
			<?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $contrato['Contrato']['cont_id']), null, sprintf(__('Esta seguro de eliminar el contrato ID #%s?, la accion eliminara tambien los gastos, evaluaciones y monitoreos asociados a el, esta seguro que desea continuar?', true), $contrato['Contrato']['cont_id'])); ?>
			<?php echo $this->Html->link(__('Excel', true), array('action' => 'excel', $contrato['Contrato']['cont_id'])); ?>
			<?php echo $this->Html->link(__('Evaluar', true), array('controller' => 'evaluaciones', 'action' => 'add', $contrato['Contrato']['cont_id'])); ?>
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
	<div>
		Buscar Contrato: <input id="busca_contrato" type="text" />&nbsp;
		&nbsp;<input type="button" value="Exportar a Excel" id="to_excel" />
	</div>
	
</div>


<?php
	include("views/sidebars/menu.php");
?>
