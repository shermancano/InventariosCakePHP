<div class="bajas view">
<h2><?php  __('Excluidos Activos Fijos');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
    	<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['ExclusionActivoFijo']['exaf_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Correlativo'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo sprintf("%012d", $entrada['ExclusionActivoFijo']['exaf_correlativo']); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Centro de Costo'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['CentroCosto']['ceco_nombre']; ?>
			&nbsp;
		</dd>		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Fecha'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo date("d-m-Y H:i:s", strtotime($entrada['ExclusionActivoFijo']['exaf_fecha'])); ?>
			&nbsp;
		</dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Número de Resolución'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['ExclusionActivoFijo']['exaf_numero_documento']; ?>
			&nbsp;
		</dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Observación'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['ExclusionActivoFijo']['exaf_observacion']; ?>
			&nbsp;
		</dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Tipo de Baja'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['DependenciaVirtual']['devi_nombre']; ?>
			&nbsp;
		</dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Motivo de Baja'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $entrada['MotivoBaja']['moba_nombre']; ?>
			&nbsp;
		</dd>
	</dl>
	
	<br />
	<br />
	<h2><?php  __('Detalle');?></h2>
        <table width="100%">
            <tr>
                <th width="20%">C&oacute;digo</th>
                <th width="65%">Producto</th>
                <th width="15%">Precio Unitario</th>                
            </tr>
            <?php
                foreach ($detalles as $det) {
            ?>
                <tr>
                    <td><?php echo $det['DetalleExclusion']['dete_codigo']; ?></td>
                    <td><?php echo $det['Producto']['prod_nombre']; ?></td>
                    <td><?php echo '$ '.number_format($det['DetalleExclusion']['dete_valor_baja'], 0, ',', '.'); ?></td>                    
                </tr>
            <?php
                }
            ?>
        </table>
        
        <p>
        <?php
        echo $this->Paginator->counter(array(
        'format' => __(utf8_encode('Pagina %page% de %pages%, mostrando %current% registros de un total de %count% total, empezando en %start%, terminando en %end%'), true)
        ));
        ?>	</p>
    
        <div class="paging">
            <?php echo $this->Paginator->prev('<< ' . __('anterior', true), array(), null, array('class' => 'disabled'));?>
         | 	<?php echo $this->Paginator->numbers();?>
     |
            <?php echo $this->Paginator->next(__('siguiente', true) . ' >>', array(), null, array('class' => 'disabled'));?>
        </div>
</div>
<?php
	include("views/sidebars/menu.php");
?>