<div class="mantenciones index">
	<h2><?php __('Mantención de Activos Fijos');?></h2>
    <table cellpadding="0" cellspacing="0">
    	<tr>
            <th width="4%">&nbsp;</th>
            <th><?php echo $this->Paginator->sort('#', 'ActivoFijoMantencion.afma_correlativo');?></th>
            <th><?php echo $this->Paginator->sort('Centro de Costo', 'CentroCosto.ceco_nombre');?></th>
            <th><?php echo $this->Paginator->sort('Número de Factura', 'ActivoFijoMantencion.afma_numero_factura');?></th>
            <th><?php echo $this->Paginator->sort('Fecha de Factura', 'ActivoFijoMantencion.afma_fecha_factura');?></th>                            
            <th class="actions"><?php __('Acciones');?></th>
    	</tr>
    <?php
    $i = 0;
    foreach ($mantenciones as $baaf):
        $class = null;
        if ($i++ % 2 == 0) {
            $class = ' class="altrow"';
        }
    ?>
    <tr<?php echo $class;?>>
        <td>
           <img src="/img/icon-yellow.png" border="0" title="Entrada directa" alt="0" />
           &nbsp;
        </td>
        <td><?php echo sprintf("%012d", $baaf['ActivoFijoMantencion']['afma_correlativo']); ?>&nbsp;</td>                   
        <td><?php echo $baaf['CentroCosto']['ceco_nombre'];?></td>
        <td><?php echo $baaf['ActivoFijoMantencion']['afma_numero_factura'];?></td>
        <td><?php echo date('d-m-Y', strtotime($baaf['ActivoFijoMantencion']['afma_fecha_factura']));?></td>
        <td class="actions">
            <?php echo $this->Html->link(__('Ver', true), array('action' => 'view', $baaf['ActivoFijoMantencion']['afma_id']), null, null); ?>                
            <?php echo $this->Html->link(__('Editar', true), array('action' => 'edit', $baaf['ActivoFijoMantencion']['afma_id']), null, null); ?>                
            <?php echo $this->Html->link(__('Comprobante', true), array('action' => 'comprobante_mantencion', $baaf['ActivoFijoMantencion']['afma_id']), null, null); ?>
            <?php echo $this->Html->link(__('Eliminar', true), array('action' => 'delete', $baaf['ActivoFijoMantencion']['afma_id']), null, sprintf(__('La accion eliminara la mantención seleccionada, ¿Desea continuar?', true), $baaf['ActivoFijoMantencion']['afma_id']));?>
        </td>
    </tr>
<?php endforeach; ?>
    </table>
    <p>
    <?php
    echo $this->Paginator->counter(array(
    'format' => __(utf8_encode('Pagina %page% de %pages%, mostrando %current% registros de un total de %count% total, empezando en %start%, terminando en %end%'), true)
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
            <td width="3%"><img src="/img/icon-yellow.png" border="0" alt="0" /></td>
            <td>Entradas directas.</td>
        </tr>
    </table>
    <br />
</div>
<?php
	include("views/sidebars/menu.php");
?>