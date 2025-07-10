<script type="text/javascript" src="/js/modelos/index.js"></script>
<div class="modelos index">
	<h2><?php __('Modelos');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('Nombre', 'mode_nombre');?></th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($modelos as $modelo):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $modelo['Modelo']['mode_nombre']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Editar', true), array('action' => 'edit', $modelo['Modelo']['mode_id'])); ?>
			<?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $modelo['Modelo']['mode_id']), null, __('La accion eliminara el modelo y todas las entradas asociadas a este, esta seguro que desea continuar?', true)); ?>
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
