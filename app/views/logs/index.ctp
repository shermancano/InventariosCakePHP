<script language="javascript" type="text/javascript" src="/js/Log/index.js"></script>
<script language="javascript" type="text/javascript">
	$(document).ready(function () {

		$('input:radio').click(function() {
			if ($(this).val() === '1') {
				$('#span_criterio').show();
				$('#span_accion').hide();
			} else if ($(this).val() === '2') {
				$('#span_criterio').hide();
				$('#span_accion').show();
			}

			$("#codigo").val("");
			$("#accion").val(""); 	
		});

		$('#btn_limpiar').click(function(event) {
			location.href = '/logs/index';
		});

		<?php
			if (!empty($criterio) && !empty($opcion)) {
		?>
				$("#codigo").val("<?php echo $criterio;?>");
				$("#LogsOpcion"+<?php echo $opcion;?>).attr("checked", true);
				$("#accion").val("<?php echo $accion;?>");
		<?php
			}
		?>

		<?php
			if (!empty($accion) && !empty($opcion)) {
		?>
				$("#codigo").val("<?php echo $criterio;?>");
				$("#LogsOpcion"+<?php echo $opcion;?>).attr("checked", true);
				$("#accion").val("<?php echo $accion;?>");

				<?php
					if ($opcion == 1) {
				?>
						$('#span_criterio').show();
						$('#span_accion').hide();
				<?php
					} else {
				?>
						$('#span_criterio').hide();
						$('#span_accion').show();
				<?php
					}
				?>
		<?php
			}
		?>
	});
</script>

<div id="params_dialog"></div>
<div class="form log">
	<h2><?php __("Registro Logs de Sistema"); ?></h2>
	<fieldset>
        <legend><?php __('Búsqueda');?></legend>
        <?php echo $this->Form->create('Logs', array('id' => 'FormLogs', 'url' => '/logs/index'));?>
            <table width="100%" id="tabla_busqueda">
                <tbody>
                    <tr>
                        <td style="border-bottom:none;">
                            <?php 
                                echo $this->Form->input('opcion', array('type' => 'radio', 'options' => array(1 => 'Nombre Centro de Costo', 2 => 'Acción'), 'value' => 1, 'legend' => false));
                            ?>
                        </td>
                    </tr>
					<tr>
						<td style="width:65%; vertical-align:bottom; border-bottom: medium none; background: none;">
							<span id="span_criterio" class="input select required">
                                <label>Ingrese Criterio</label>
                                <input type="text" id="codigo" style="width:600px;" name="data[Logs][busqueda]" />                                 
                            </span>
							<span id="span_accion" class="input select required" style="display:none;">
                                <label>Seleccione Criterio</label>
								<select id="accion" name="data[Logs][busqueda_accion]">
									<option value="">-- Seleccione Opción --</option>
									<?php
										foreach ($acciones as $key => $val) {
									?>
										<option value="<?php echo $val; ?>"><?php echo $val; ?></option>
									<?php
										}
									?>
								</select>                            
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
            	<th><?php echo $this->Paginator->sort('Usuario', 'Usuario.usua_nombre');?></th>            
                <th><?php echo $this->Paginator->sort('Nombre', 'Usuario.usua_nombre');?></th>
				<th><?php echo $this->Paginator->sort('Fecha/Hora', 'Log.logs_fecha');?></th>
                <th><?php echo $this->Paginator->sort('Dirección IP', 'Log.logs_ip');?></th>
				<th><?php echo __('Acción');?></th>
                <th><?php echo $this->Paginator->sort('Parámetros');?></th>
            </tr>
            <?php
				$i = 0;
				foreach ($logs as $log):
					$class = null;
					if ($i++ % 2 == 0) {
						$class = ' class="altrow"';
					}
			?>
			<tr<?php echo $class;?>>
                <td><?php echo $log['Usuario']['usua_username']; ?>&nbsp;</td>
                <td><?php echo $log['Usuario']['usua_nombre']; ?>&nbsp;</td>
                <td><?php echo date("d-m-Y H:i:s", strtotime($log['Log']['logs_fecha'])); ?>&nbsp;</td>
                <td><?php echo $log['Log']['logs_ip']; ?>&nbsp;</td>
                <td><?php echo ($log['Log']['logs_accion']); ?>&nbsp;</td>
                <td class="actions">
                	<input type="button" value="Mostrar" class="btn_mostrar" />
                    <div class="params" style="display:none">
                        <?php echo $log['Log']['logs_parametros']; ?>
                    </div>
                    <?php
					try {
						$parsed = eval("return " . $log['Log']['logs_parametros'] . ";");
						
						if (isset($parsed["url"]) && 
							isset($parsed["data"]['CentroCosto']['ceco_id']) &&
							isset($parsed["_method"]) &&
							$parsed["_method"] == "PUT") {
				?>
					<?php echo $this->Html->link(__('Editar', true), array('class' => 'btn_mostrar', 'style' => 'padding: 4px 9px;', 'action' => 'edit', 'controller' => 'centros_costos', $parsed["data"]['CentroCosto']['ceco_id'])); ?>
				<?php
						}
					} catch (Exception $e) {

					}
				?>
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
</div>
<?php
	include("views/sidebars/menu.php");
?>
