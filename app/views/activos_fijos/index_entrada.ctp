<script language="Javascript" type="text/javascript" src="/js/activos_fijos/index_entrada.js"></script>

<div id="rechazo" style="display:none;">
	<table width="100%" border="0" id="table_rechazo">
		<tr>
			<td width="32%">
				<span id="span_rechazo">
					<label>Ingrese motivo:</label>
				</span>
			</td>
			<td>
				<input type="hidden" id="dialog_acfi_id" />
				<textarea style="width:270px; height:100px;" id="motivo" rows="0" cols="0"></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: left;">
				<input type="button" value="Rechazar" id="rechazar_entrada" />&nbsp;<img style="display:none;" id="loader" src="/img/ajax-loader.gif" alt="0" />
			</td>
		</tr>
	</table>
</div>

<div class="entrada index">
	<h2><?php __('Entrada de Activos Fijos');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th width="4%">&nbsp;</th>
			<th><?php echo $this->Paginator->sort(utf8_encode('Código'), 'ActivoFijo.acfi_correlativo');?></th>
            <th><?php echo $this->Paginator->sort('Centro de Costo Padre', 'CentroCostoPadre.ceco_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Centro de Costo', 'CentroCosto.ceco_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Proveedor', 'Proveedor.prov_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Fecha', 'Existencia.exis_fecha');?></th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($activos_fijos as $acfi):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td>
			<?php
				if ($acfi['ActivoFijo']['esre_id'] == 2) {
			?>
				<a rel="<?php echo $acfi['CentroCostoPadre']['ceco_nombre']; ?>|<?php echo $acfi['ActivoFijo']['acfi_id']; ?>" href="javascript:;" class="recepcionar"><img src="/img/test-fail-icon.png" border="0" title="Este traslado a&uacute;n no ha sido recepcionado por <?php echo $acfi['CentroCosto']['ceco_nombre']; ?>" alt="0"/></a>				
			<?php
				} elseif ($acfi['ActivoFijo']['esre_id'] == 1 && $acfi['ActivoFijo']['ceco_id_padre'] != "") {
			?>
				<img src="/img/test-pass-icon.png" border="0" title="Entrada recepcionada" alt="0" />
			<?php
				} else {
			?>
				<img src="/img/icon-yellow.png" border="0" title="Entrada directa" alt="0" />
			<?php
				}
			?>
			&nbsp;
		</td>
		<td><?php echo sprintf("%012d", $acfi['ActivoFijo']['acfi_correlativo']); ?>&nbsp;</td>
        <td><?php echo $acfi['CentroCostoPadre']['ceco_nombre']; ?>&nbsp;</td>
		<td><?php echo $acfi['CentroCosto']['ceco_nombre']; ?>&nbsp;</td>
		<td><?php echo $acfi['Proveedor']['prov_nombre']; ?>&nbsp;</td>
		<td><?php echo date("d-m-Y H:i:s", strtotime($acfi['ActivoFijo']['acfi_fecha'])); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Ver', true), array('action' => 'view_entrada', $acfi['ActivoFijo']['acfi_id']), null, null); ?>
			<?php
				if (!isset($acfi['ActivoFijo']['acfi_id_padre'])) {
					echo $this->Html->link(__('Editar', true), array('action' => 'edit_entrada', $acfi['ActivoFijo']['acfi_id']), null, null);
					// Se comenta opción "eliminar" para que solamente se puedan eliminar los bienes en modulo de "Consulta/Códigos de Barra"
					// y solo podrán editar las entradas.
					//echo $this->Html->link(__('Eliminar', true), array('action' => 'delete_entrada', $acfi['ActivoFijo']['acfi_id']), array('title' => 'Eliminar toda la entrada'), __('La accion eliminara la entrada seleccionada, esta seguro que desea continuar?', true));
				}
			?>
			<?php echo $this->Html->link(__('Comprobante', true), array('action' => 'comprobante_entrada', $acfi['ActivoFijo']['acfi_id']), null, null); ?>
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
    <br />
    <fieldset>
    	<legend><?php __(utf8_encode('Búsqueda'));?></legend>
        	<table width="100%" class="detalle_form" border="0">
				<tr>
					<td width="58%">
						<span class="input select required">
							<label>Ingrese b&uacute;squeda</label>
							<input type="text" id="busqueda_entrada_activo_fijo" style="width:456px;" />
							<input type="hidden" id="prod_id" />
						</span>
					</td>
                </tr>
			</table>
    </fieldset>
</div>

<?php
	include("views/sidebars/menu.php");
?>