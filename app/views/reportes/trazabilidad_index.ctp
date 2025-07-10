<script language="Javascript" type="text/javascript" src="/js/trazabilidad/index.js"></script>
<div class="trazabilidad index">
	<?php echo $this->Form->create('Reporte', array('id' => 'FormTrazabilidad'));?>
	<fieldset>
    	<legend><?php __('Reporte de Trazabilidad');?></legend>
        <table width="100%" id="tabla_trazabilidad">
        	<tbody>
            	<tr class="tr_codigo">
                	<td style="width:65%; vertical-align:bottom; border-bottom: medium none;">
                    	<span class="input select required">
                            <label>Ingrese nombre o c&oacute;digo de producto</label>
                            <input type="text" class="codigo" rel="0" />
                            <input type="hidden" name="data[Reporte][0][traf_codigo]" id="Reporte0TrafCodigo" />
                        </span>
                    </td>
                    <td class="td_btn" style="vertical-align:bottom; padding: 9px 0px; border-bottom: medium none;">
                    	<a id="add_codigo_barra"><img src="/img/add.png" title="Presione aquí para agregar mas bienes" alt="" /></a>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <?php
			echo $this->Form->input('ceco_id', array('type' => 'hidden', 'value' => $ceco_id));
			//echo $this->Form->input('Reporte.0.traf_codigo', array('type' => 'text', 'label' => 'Código de Barra', 'onKeyPress' => 'return validchars(event, num)'));
		?>
    </fieldset>    
    <?php echo $this->Form->end(__('Generar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>