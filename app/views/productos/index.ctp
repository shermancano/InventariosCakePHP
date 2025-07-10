<script type="text/javascript" src="/js/productos/index.js"></script>

<div class="gastos index">
	<h2><?php __('Catalogo de Productos');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
		    <th><?php echo $this->Paginator->sort('Código Interno', 'prod_codigo');?></th>
			<th><?php echo $this->Paginator->sort('Nombre', 'Producto.prod_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Familia', 'Familia.fami_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Grupo', 'Grupo.grup_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Tipo de Bien', 'TipoBien.tibi_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Tipo de Unidad', 'Unidad.unid_nombre');?></th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($productos as $prod):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $prod['Producto']['prod_codigo']; ?>&nbsp;</td>
		<td><?php echo $prod['Producto']['prod_nombre']; ?>&nbsp;</td>
		<td><?php echo $prod['Grupo']['Familia']['fami_nombre']; ?>&nbsp;</td>
		<td><?php echo $prod['Grupo']['grup_nombre']; ?>&nbsp;</td>
		<td><?php echo $prod['TipoBien']['tibi_nombre']; ?>&nbsp;</td>
		<td><?php echo $prod['Unidad']['unid_nombre']; ?>&nbsp;</td>
		<td class="actions">
        	<?php
            	if ($prod['ProductoImagen']['prod_imagen'] > 0) {
					echo $this->Html->link(__('Ver Imagen', true), array('action' => 'view_imagen', $prod['Producto']['prod_id']), array('target' => '_blank'));
				}
			?>
			<?php echo $this->Html->link(__('Editar', true), array('action' => 'edit', $prod['Producto']['prod_id'])); ?>
			<?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $prod['Producto']['prod_id']), null, __('La accion eliminara el Centro de Costo y todos los bienes asociados a el, esta seguro que desea continuar?', true)); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Página %page% de %pages%, mostrando %current% registros de un total de %count% total, empezando en %start%, terminando en %end%', true)
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
    	<legend>Búsqueda</legend>
        	<table width="100%" border="0" class="detalle_form">
				<tbody><tr>
					<td width="58%">
						<span class="input select required">
							<label>Ingrese búsqueda</label>
							<input type="text" style="width:456px;" id="busqueda_producto" />
							<input type="hidden" id="prod_id">
						</span>
					</td>
                </tr>
			</tbody></table>
    </fieldset>
	
</div>

<?php
	include("views/sidebars/menu.php");
?>
