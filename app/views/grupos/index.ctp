<script type="text/javascript" src="/js/grupos/index.js"></script>
<div class="grupos index">
	<h2><?php __('Grupos');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('Nombre', 'Grupo.grup_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Familia', 'Familia.fami_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Tipo de Familia', 'TipoFamilia.tifa_nombre');?></th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($grupos as $grupo):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $grupo['Grupo']['grup_nombre']; ?>&nbsp;</td>
		<td><?php echo $grupo['Familia']['fami_nombre']; ?>&nbsp;</td>
		<td><?php echo $grupo['Familia']['TipoFamilia']['tifa_nombre']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Editar', true), array('action' => 'edit', $grupo['Grupo']['grup_id'])); ?>
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
