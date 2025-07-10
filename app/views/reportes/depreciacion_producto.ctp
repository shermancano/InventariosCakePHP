<script language="Javascript" type="text/javascript" src="/js/reportes/depreciacion_producto.js"></script>
<div class="trazabilidad index">
	<?php echo $this->Form->create('Reporte', array('id' => 'FormTrazabilidad'));?>
	<fieldset>
    	<legend><?php __('Reporte de Depreciación por Producto');?></legend>
        <table width="100%" id="tabla_trazabilidad">
        	<tbody>
            	<tr class="tr_codigo">
                	<td style="width:65%; vertical-align:bottom; border-bottom: medium none;">
                    	<span class="input select required">
                            <label>Ingrese nombre o c&oacute;digo de producto</label>
                            <input type="text" class="codigo" rel="0" />
                            <input type="hidden" name="data[Reporte][ubaf_codigo]" id="ReporteUbafCodigo" />
                        </span>
                    </td>                    
                </tr>
            </tbody>
        </table>
        
        <?php
			echo $this->Form->input('ceco_id', array('type' => 'hidden', 'value' => $ceco_id));			
		?>
    </fieldset>    
    <?php echo $this->Form->end(__('Generar', true));?>
</div>
<?php
	include("views/sidebars/menu.php");
?>