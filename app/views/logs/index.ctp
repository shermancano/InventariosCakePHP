<script language="javascript" type="text/javascript" src="/js/Log/index.js"></script>

<div id="params_dialog"></div>
<div class="form log">

	<h2><?php __("Registro Logs de Sistema"); ?></h2>
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
                <td>
                	<input type="button" value="Mostrar" class="btn_mostrar" />
                    <div class="params" style="display:none">
                        <?php echo $log['Log']['logs_parametros']; ?>
                    </div>
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