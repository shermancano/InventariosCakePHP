<div class="exclusiones index">
	<h2><?php __('Excluidos Activos Fijos');?></h2>
    <table cellpadding="0" cellspacing="0">
    <tr>
            <th width="4%">&nbsp;</th>
            <th><?php echo $this->Paginator->sort('Código', 'BajaActivoFijo.baaf_correlativo');?></th>                
            <th><?php echo $this->Paginator->sort('Centro de Costo', 'CentroCosto.ceco_nombre');?></th>
            <th><?php echo $this->Paginator->sort('Tipo de Baja', 'DependenciaVirtual.devi_nombre');?></th>
            <th><?php echo $this->Paginator->sort('Fecha', 'BajaActivoFijo.baaf_fecha');?></th>
            <th class="actions"><?php __('Acciones');?></th>
    </tr>
    <?php
    $i = 0;
    foreach ($exclusiones_activos_fijos as $exaf):
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
        <td><?php echo sprintf("%012d", $exaf['ExclusionActivoFijo']['exaf_correlativo']); ?>&nbsp;</td>            
        <td><?php echo $exaf['CentroCosto']['ceco_nombre']; ?>&nbsp;</td>
        <td><?php echo $exaf['DependenciaVirtual']['devi_nombre']; ?>&nbsp;</td>
        <td><?php echo date("d-m-Y H:i:s", strtotime($exaf['ExclusionActivoFijo']['exaf_fecha'])); ?>&nbsp;</td>
        <td class="actions">
            <?php echo $this->Html->link(__('Ver', true), array('action' => 'view', $exaf['ExclusionActivoFijo']['exaf_id']), null, null); ?>                
            <?php echo $this->Html->link(__('Comprobante', true), array('action' => 'comprobante_exclusion_pdf', $exaf['ExclusionActivoFijo']['exaf_id']), null, null); ?>
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