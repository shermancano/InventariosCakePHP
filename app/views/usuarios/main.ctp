<div class="form main" id="form_main">
<fieldset>
    	<legend><?php echo __("Información");?></legend>
        	<table width="30%" align="left" border="0">
            	<tr>
                	<td width="20%"><strong>USUARIO CONECTADO: </strong></td>
                    <td><?php echo $usua_nombre; ?></td>
                </tr>
                <tr>
                	<td><strong>PERFIL: </strong></td>
                     <td><?php echo $perfil_nom; ?></td>
                </tr>
                <tr>
                	<td><strong>ÚLTIMO ACCESO: </strong></td>
                     <td><?php echo date("d-m-Y H:i:s", strtotime($ultimo_acceso)); ?></td>
                </tr>
            </table>
    </fieldset>
	<fieldset>
        <legend><?php __('Búsqueda');?></legend>
        <?php echo $this->Form->create('ActivoFijo', array('id' => 'FormActivoFijo', 'url' => '/usuarios/main'));?>
            <table width="100%" id="tabla_busqueda">
                <tbody>
                    <tr>
                        <td>
                            <?php 
                                echo $this->Form->input('opcion', array('type' => 'radio', 'options' => array(1 => 'Número de Factura', 2 => 'Orden de Compra', 3 => 'Código de Barra', 4 => 'Nombre Producto', 5 => 'Modelo', 6 => 'Marca', 7 => 'Color', 8 => 'Serie', 9 => 'Financiamiento', 10 => 'Nro Resolución', 11 => 'Proveedor'), 'value' => 1, 'legend' => false));
                            ?>
                        </td>
                    </tr>
                    <tr class="tr_codigo">
                        <td style="width:65%; vertical-align:bottom; border-bottom: medium none;">
                            <span class="input select required">
                                <label>Ingrese Criterio</label>
                                <input type="text" id="codigo" style="width:600px;" name="data[ActivoFijo][busqueda]" />                                 
                            </span>
                        </td>
                        <td class="td_btn actions" style="vertical-align:bottom; padding: 9px 0px; border-bottom: medium none;">                            
                            <input type="submit" value="Buscar" id="btn_buscar" />
                            <input type="button" value="Ver Todo" id="btn_limpiar" />
                        </td>
                    </tr>
                    
                </tbody>
            </table>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td colspan="5">
                         <img src="/img/info.png" alt="0" /> El listado muestra todos los bienes del centro de costo junto con los bienes de sus dependencias. <br><br>
                    </td>
                </tr>
                <tr>
                    <th><?php echo $this->Paginator->sort('Código', 'UbicacionActivoFijo.ubaf_codigo');?></th>
                    <th><?php echo $this->Paginator->sort('Producto', 'Producto.prod_nombre');?></th>
                    <th><?php echo $this->Paginator->sort('Centro de Costo', 'CentroCosto.ceco_nombre');?></th>
                    <th><?php echo $this->Paginator->sort('Nº Factura', 'ActivoFijo.acfi_nro_documento');?></th>
                    <th><?php echo $this->Paginator->sort('Orden Compra', 'ActivoFijo.acfi_orden_compra');?></th>
                    <th class="actions"><?php __('Acciones');?></th>
                </tr>
                <?php
                    $i = 0;
                    $indice = 0;
                    foreach ($detalles as $deta):
                        $class = null;
                        if ($i++ % 2 == 0) {
                            $class = ' class="altrow"';
                        }
                ?>
                    <tr<?php echo $class;?>>
                        <td><?php echo $deta['UbicacionActivoFijo']['ubaf_codigo']; ?>&nbsp;</td>
                        <td width="30%"><?php echo $deta['Producto']['prod_nombre']; ?>&nbsp;</td>
                        <td><?php echo $deta['CentroCosto']['ceco_nombre']; ?>&nbsp;</td>
                        <td><?php echo $deta['ActivoFijo']['acfi_nro_documento']; ?>&nbsp;</td>
                        <td><?php echo $deta['ActivoFijo']['acfi_orden_compra']; ?>&nbsp;</td>
                        <td class="actions">                            
                            <?php echo $this->Html->link(__('Detalle Ingreso', true), array('action' => 'view_busqueda',$deta['ActivoFijo']['acfi_id'], $deta['UbicacionActivoFijo']['ubaf_codigo']), null, null); ?>                                                      
                            <div class="params" style="display:none">
                                <?php echo ""; ?>
                            </div>  
                        </td>
                    </tr>
                <?php
                    $indice++; 
                    endforeach; 
                ?>
                </table>                
                <p>
                <?php
                echo $this->Paginator->counter(array(
                'format' => __('Página %page% de %pages%, mostrando %current% registros de un total de %count% total, empezando en %start%, terminando en %end%', true)
                ));
                ?>
                </p>

                <div class="paging">
                    <?php echo $this->Paginator->prev('<< ' . __('anterior', true), array(), null, array('class'=>'disabled'));?>
                 |  <?php echo $this->Paginator->numbers();?>
             |
                    <?php echo $this->Paginator->next(__('siguiente', true) . ' >>', array(), null, array('class' => 'disabled'));?>
                </div>
                <br />                
        <?php echo $this->Form->end(); ?>
    </fieldset>
	<fieldset>
    	<legend><?php echo __("Menu Principal"); ?></legend>
            <table width="100%" border="0">
                <tr>
                    <td style="width:25%"><font size="3"><strong>Activos Fijos</strong></font></td>                    
                    <td style="width:75%"><font size="3"><strong>Reportes</strong></font></td>
                </tr>
                <tr>
                    <td>&diams; <a href="/activos_fijos/index_entrada">Entradas</a></td>
                    <td>&diams; <a href="/reportes/stock">Reportes de Stock</a></td>
                </tr>
                <tr>
                    <td class="td_sub"><a href="/activos_fijos/add_entrada">Nueva Entrada</a></td>                    
                    <td>&diams; <a href="/reportes/transito">Stock en Tránsito</a></td>
                </tr>
                <tr>
                    <td>&diams; <a href="/activos_fijos/index_traslado">Traslados</a></td>
                    <td>&diams; <a href="/reportes/gastos_cta_contable">Gastos por Cuenta Contable</a></td>
                </tr>
                <tr>
                    <td class="td_sub"><a href="/activos_fijos/add_traslado">Nuevo Traslado</a></td>
                    <td>&diams; <a href="/reportes/productos">Catalogo de Productos</a></td>
                </tr>
                <tr>
                    <td>&diams; <a href="/bajas_activos_fijos/index">Bajas</a></td>
                    <td>&diams; <a href="/reportes/bajas_activos_fijos">Bajas Activos Fijos</a></td>
                </tr>
                <tr>
                    <td class="td_sub"><a href="/bajas_activos_fijos/add">Nueva Baja</a></td>
                    <td>&diams; <a href="/reportes/activos_fijos">Reporte de Activos Fijos</a></td>
                </tr>
                <tr>
                    <td>&diams; <a href="/rechazos_activos_fijos">Rechazos</a></td>
                    <td>&diams; <a href="/reportes/activos_fijos_general">Reporte General Activos Fijos</a></td>
                </tr>
                <tr>
                    <td>&diams; <a href="/activos_fijos/codigos_barra">Consulta/Cód. Barra</a></td>
                    <td>&diams; <a href="/reportes/traslados_por_fecha">Traslados por Fechas</a></td>
                </tr>
                <tr>
                    <td>&diams; <a href="/activos_fijos/plancheta">Plancheta</a></td>
                    <td>&diams; <a href="/logs/index">Ver Log</a></td>
                </tr>
                <tr>
                    <td>&diams; <a href="/depreciaciones">Depreciación</a></td>
                    <td>&diams; <a href="/reportes/trazabilidad_index">Reporte Trazabilidad</a></td>
                </tr>
                <tr>
                    <td class="td_sub"><a href="/depreciaciones/add">Nuevo Cálculo</a></td>
                    <td>&diams; <a href="/reportes/depreciacion_producto">Depreciación por Producto</a></td>
                </tr>
                <tr>
                    <td>&diams; <a href="/mantenciones">Mantención</a></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class="td_sub"><a href="/mantenciones/add">Nueva Mantención</a></td>
                    <td>&nbsp;</td>
                </tr>
            </table>        
    </fieldset>
    <fieldset>
    	<legend><?php echo __("Mantenedores"); ?></legend>
        	<table width="100%" border="0">
            	<tr>
                	<td width="25%">&diams; <a href="/proveedores">Proveedores</a></td>
                    <td width="25%">&diams; <a href="/colores">Colores</a></td>
                    <td width="25%">&diams; <a href="/marcas">Marcas</a></td>
                    <td width="25%">&diams; <a href="/modelos">Modelos</a></td>
                </tr>
                <tr>
                	<td class="td_sub"><a href="/proveedores/add">Añadir Proveedor</a></td>
                    <td class="td_sub"><a href="/colores/add">Añadir Color</a></td>
                    <td class="td_sub"><a href="/marcas/add">Añadir Marca</a></td>
                    <td class="td_sub"><a href="/modelos/add">Añadir Modelo</a></td>
                </tr>
                <tr>
                	<td>&diams; <a href="/cuentas_contables">Cuentas Contables</a></td>
                    <td>&diams; <a href="/situaciones">Situaciones</a></td>
                    <td>&diams; <a href="/propiedades">Propiedades</a></td>
                    <td>&diams; <a href="/responsables">Responsables</a></td>
                </tr>
                <tr>
                	<td class="td_sub"><a href="/cuentas_contables/add">Añadir Cuenta</a></td>
                    <td class="td_sub"><a href="/situaciones/add">Añadir Situacion</a></td>
                    <td class="td_sub"><a href="/propiedades/add">Añadir Propiedad</a></td>
                    <td class="td_sub"><a href="/responsables/add">Añadir Responsable</a></td>
                </tr>
                <tr>
                	<td>&diams; <a href="/familias">Familias</a></td>
                    <td>&diams; <a href="/grupos">Grupos</a></td>
                    <td>&diams; <a href="/productos">Productos</a></td>
                    <td>&diams; <a href="/centros_costos">Centros de Costo</a></td>
                </tr>
                <tr>
                	<td class="td_sub"><a href="/familias/add">Añadir Familia</a></td>
                    <td class="td_sub"><a href="/grupos/add">Añadir Grupo</a></td>
                    <td class="td_sub"><a href="/productos/add">Añadir Producto</a></td>
                    <td class="td_sub"><a href="/centros_costos/add">Nuevo Centro de Costo</a></td>
                </tr>
                <tr>
                	<td>&diams; <a href="/tipos_documentos">Tipos de Documentos</a></td>
                    <td>&diams; <a href="/financiamientos">Financiamientos</a></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                	<td class="td_sub"><a href="/tipos_documentos/add">Nuevo Tipo de Documento</a></td>
                    <td class="td_sub"><a href="/financiamientos/add">Nuevo Financiamiento</a></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
    </fieldset>
</div>
<?php
	include("views/sidebars/menu.php");
?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#btn_limpiar').click(function(event) {
            location.href = '/usuarios/main';
        });

        <?php
            if (!empty($criterio) && !empty($opcion)) {
        ?>
                $('#codigo').val('<?php echo $criterio;?>');
                $('#ActivoFijoOpcion'+<?php echo $opcion;?>).attr('checked', true);
        <?php
            }
        ?>
    });
</script>
