<script language="javascript" type="text/javascript">
	$(document).ready(function () {
		$('#btn_limpiar').click(function(event) {
			location.href = '/usuarios/index';
		});

		<?php
			if (!empty($criterio) && !empty($opcion)) {
		?>
				$('#codigo').val('<?php echo $criterio;?>');
				$('#UsuarioOpcion'+<?php echo $opcion;?>).attr('checked', true);
		<?php
			}
		?>
	});
</script>
<div class="usuarios index">
	<h2><?php __('Usuarios');?></h2>
	<fieldset>
        <legend><?php __(utf8_encode('Búsqueda'));?></legend>
        <?php echo $this->Form->create('Usuario', array('id' => 'Usuarios', 'url' => '/usuarios/index'));?>
            <table width="100%" id="tabla_busqueda">
                <tbody>
                    <tr>
                        <td style="border-bottom:none;">
                            <?php 
                                echo $this->Form->input('opcion', array('type' => 'radio', 'options' => array(1 => 'Nombre', 2 => 'Rut'), 'value' => 1, 'legend' => false));
                            ?>
                        </td>
                    </tr>
					<tr>
						<td style="width:65%; vertical-align:bottom; border-bottom: medium none; background: none;">
							<span id="span_criterio" class="input select required">
                                <label>Ingrese Criterio</label>
                                <input type="text" id="codigo" style="width:600px;" name="data[Usuario][busqueda]" />                                 
                            </span>
                        </td>
                        <td class="td_btn actions" style="vertical-align:bottom; padding: 9px 0px; border-bottom: medium none; background: none;">                            
                            <input type="submit" value="Buscar" id="btn_buscar" />
                            <input type="button" value="Ver Todo" id="btn_limpiar" />
                        </td>
                    </tr>                    
                </tbody>
            </table>
			<?php echo $this->Form->end(); ?>
    	</fieldset>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('Perfil', 'perf_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Nombre', 'usua_nombre');?></th>
			<th><?php echo $this->Paginator->sort('Usuario', 'usua_username');?></th>
			<th><?php echo $this->Paginator->sort('Estado', 'esre_nombre');?></th>
			<th><?php echo $this->Paginator->sort(utf8_encode('Último Acceso'), 'esre_nombre');?></th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($usuarios as $usuario):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $usuario['Perfil']['perf_nombre']; ?>&nbsp;</td>
		<td><?php echo $usuario['Usuario']['usua_nombre']; ?>&nbsp;</td>
		<td><?php echo $usuario['Usuario']['usua_username']; ?>&nbsp;</td>
		<td><?php echo $usuario['EstadoRegistro']['esre_nombre']; ?>&nbsp;</td>
		<td>
			<?php
				if ($usuario['Usuario']['usua_ultimo_acceso'] != "") {
					echo date("d-m-Y H:i:s", strtotime($usuario['Usuario']['usua_ultimo_acceso']));
				}
			?>
			&nbsp;
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Editar', true), array('action' => 'edit', $usuario['Usuario']['usua_id'])); ?>
			<?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $usuario['Usuario']['usua_id']), null, sprintf(__('Esta seguro de eliminar al usuario ID #%s?', true), $usuario['Usuario']['usua_id'])); ?>
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
