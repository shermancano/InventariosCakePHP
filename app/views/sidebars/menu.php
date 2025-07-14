<script language="javascript">
	window.onload=function(){
		var pos=window.name || 0;
		window.scrollTo(0,pos);
	}
	window.onunload=function(){
		window.name=self.pageYOffset || (document.documentElement.scrollTop+document.body.scrollTop);
	}
</script>
<div class="actions">
	<h3><?php __('Usuario'); ?></h3>
	<?php
		if (in_array('usuarios', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Mi Cuenta', true), array('controller' => 'usuarios', 'action' => 'micuenta')); ?></li>
	</ul>
	<ul>
		<li><?php echo $this->Html->link(__('Cambiar Centro de Costo', true), array('controller' => 'usuarios', 'action' => 'selCentroCosto')); ?></li>
	</ul>
	<?php
		endif;
	?>
    <ul>
		<li><?php echo $this->Html->link(__('Wizards', true), array('controller' => 'wizards', 'action' => 'index')); ?></li>
	</ul>
	<ul>
		<li><?php echo $this->Html->link(__('Salir', true), array('controller' => 'login', 'action' => 'logout')); ?></li>
	</ul>
    <!--
	<br />
    
	<h3><?php __('Adquisiciones'); ?></h3>
	<?php
		if (in_array('solicitudes', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Solicitudes', true), array('controller' => 'solicitudes', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "solicitudes") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('Nueva Solicitud'), true), array('controller' => 'solicitudes', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
		<li><?php echo $this->Html->link(__('Solicitudes Pendientes Int.', true), array('controller' => 'solicitudes', 'action' => 'pendientes_internas')); ?></li>
		<li><?php echo $this->Html->link(__('Solicitudes Pendientes Ext.', true), array('controller' => 'solicitudes', 'action' => 'pendientes_externas')); ?></li>
		<li><?php echo $this->Html->link(__('Rechazos', true), array('controller' => 'rechazos_solicitudes', 'action' => 'index')); ?></li>
	<?php
		endif;
		
		if (in_array('ordenes_compras', $menu)) :
	?>
		<li><?php echo $this->Html->link(__('Ordenes de Compra', true), array('controller' => 'ordenes_compras', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "ordenes_compras") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('Nueva Orden'), true), array('controller' => 'ordenes_compras', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	<?php
		endif;
	?>
	</ul>
	<br />
	
	<h3><?php __('Existencias'); ?></h3>
	<?php
		if (in_array('existencias', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Entradas', true), array('controller' => 'existencias', 'action' => 'index_entrada')); ?></li>
		<?php
			if ($this->params['controller'] == "existencias") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('Nueva Entrada'), true), array('controller' => 'existencias', 'action' => 'add_entrada')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<ul>
		<li><?php echo $this->Html->link(__('Traslados', true), array('controller' => 'existencias', 'action' => 'index_traslado')); ?></li>
		<?php
			if ($this->params['controller'] == "existencias") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('Nuevo Traslado'), true), array('controller' => 'existencias', 'action' => 'add_traslado')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('rechazos_existencias', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Rechazos', true), array('controller' => 'rechazos_existencias', 'action' => 'index')); ?></li>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('stocks_centros_costos', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Stocks por CS/CC', true), array('controller' => 'stocks_centros_costos', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "stocks_centros_costos") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('Nuevo Registro'), true), array('controller' => 'stocks_centros_costos', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
    -->
	<br />
	
	<?php
		if (in_array('activos_fijos', $menu)) :
	?>
	<h3><?php __('Activos Fijos'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Entradas', true), array('controller' => 'activos_fijos', 'action' => 'index_entrada')); ?></li>
		<?php
			if ($this->params['controller'] == "activos_fijos") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('Nueva Entrada'), true), array('controller' => 'activos_fijos', 'action' => 'add_entrada')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
    <ul>
		<li><?php echo $this->Html->link(__('Traslados', true), array('controller' => 'activos_fijos', 'action' => 'index_traslado')); ?></li>
		<?php
			if ($this->params['controller'] == "activos_fijos") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('Nuevo Traslado'), true), array('controller' => 'activos_fijos', 'action' => 'add_traslado')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
    <?php
		endif;
	?>
    <?php
		if (in_array('bajas_activos_fijos', $menu)) :
	?>
        <ul>
            <li><?php echo $this->Html->link(__('Bajas', true), array('controller' => 'bajas_activos_fijos', 'action' => 'index')); ?></li>
            <?php
                if ($this->params['controller'] == "bajas_activos_fijos") {
            ?>
            <li>
                <ul>
                    <li><?php echo $this->Html->link(__(utf8_encode('Nueva Baja'), true), array('controller' => 'bajas_activos_fijos', 'action' => 'add')); ?></li>
                </ul>
            </li>
            <?php
                }
            ?>
        </ul>
    <?php
		endif;
	?>
    <?php
		if (in_array('exclusiones_activos_fijos', $menu)) {
	?>
    	<ul>
            <li><?php echo $this->Html->link(__('Excluidos', true), array('controller' => 'exclusiones_activos_fijos', 'action' => 'index')); ?></li>
            <?php
                if ($this->params['controller'] == "exclusiones_activos_fijos") {
            ?>
            <li>
                <ul>
                    <li><?php echo $this->Html->link(__(utf8_encode('Nueva Exclusi�n'), true), array('controller' => 'exclusiones_activos_fijos', 'action' => 'add')); ?></li>
                </ul>
            </li>
            <?php
                }
            ?>
        </ul>        
    <?php
		}
	?>
	<?php
		if (in_array('rechazos_activos_fijos', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Rechazos', true), array('controller' => 'rechazos_activos_fijos', 'action' => 'index')); ?></li>
	</ul>
	<?php
		endif;
	?>	
	<ul>
		<li><?php echo $this->Html->link(__(utf8_encode('Consulta/C�digos de Barra'), true), array('controller' => 'activos_fijos', 'action' => 'codigos_barra')); ?></li>
	</ul>
	<ul>
		<li><?php echo $this->Html->link(__(utf8_encode('Plancheta'), true), array('controller' => 'activos_fijos', 'action' => 'plancheta')); ?></li>
	</ul>
    <ul>
    	<li><?php echo $this->Html->link(__(utf8_encode('Depreciaci�n'), true), array('controller' => 'depreciaciones', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "depreciaciones") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('Nuevo c�lculo'), true), array('controller' => 'depreciaciones', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<ul>
		<li><?php echo $this->Html->link(__(utf8_encode('Carga Masiva'), true), array('controller' => 'activos_fijos', 'action' => 'carga_masiva')); ?></li>
	</ul>
    <?php
		if (in_array('detalles_activos_fijos_mantenciones', $menu)) {
	?>
    	<ul>
            <li><?php echo $this->Html->link(__('Mantenciones', true), array('controller' => 'detalles_activos_fijos_mantenciones', 'action' => 'index')); ?></li>
            <?php
                if ($this->params['controller'] == "detalles_activos_fijos_mantenciones") {
            ?>
            <li>
                <ul>
                    <li><?php echo $this->Html->link(__(utf8_encode('Nueva Mantenci�n'), true), array('controller' => 'detalles_activos_fijos_mantenciones', 'action' => 'add')); ?></li>
                </ul>
            </li>
            <?php
                }
            ?>
        </ul>        
    <?php
		}
	?>
    <br />
   
    <?php
		if (in_array('reportes', $menu)) :
	?>
    <h3><?php __('Reportes'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Stock', true), array('controller' => 'reportes', 'action' => 'stock')); ?></li>
		<li><?php echo $this->Html->link(__(utf8_encode('Stock en Tránsito'), true), array('controller' => 'reportes', 'action' => 'transito')); ?></li>
		<li><?php echo $this->Html->link(__('Gastos por Cuenta Cont.', true), array('controller' => 'reportes', 'action' => 'gastos_cta_contable')); ?></li>
		<li><?php echo $this->Html->link(__('Catalogo de Productos', true), array('controller' => 'reportes', 'action' => 'productos')); ?></li>
		<!--<li><?php echo $this->Html->link(__('Existencias', true), array('controller' => 'reportes', 'action' => 'existencias')); ?></li>-->
        <li><?php echo $this->Html->link(__('Activos Fijos', true), array('controller' => 'reportes', 'action' => 'activos_fijos')); ?></li>
        <li><?php echo $this->Html->link(__('Activos Fijos General', true), array('controller' => 'reportes', 'action' => 'activos_fijos_general'));?></li>
        <li><?php echo $this->Html->link(__('Activos Fijos Migracion', true), array('controller' => 'reportes', 'action' => 'activos_fijos_migracion'));?></li>
	<li><?php echo $this->Html->link(__('Traslados por Fechas', true), array('controller' => 'reportes', 'action' => 'traslados_por_fecha')); ?></li>
        <li><?php echo $this->Html->link(__('Stock Activo Fijo CS/CC', true), array('controller' => 'reportes', 'action' => 'stock_centro_costo'));?></li>
        <li><?php echo $this->Html->link(__('Bajas Activos Fijos', true), array('controller' => 'reportes', 'action' => 'bajas_activos_fijos'));?></li>
        <li><?php echo $this->Html->link(__('Trazabilidad', true), array('controller' => 'reportes', 'action' => 'trazabilidad_index'));?></li>
        <li><?php echo $this->Html->link(__(utf8_encode('Depreciación por Producto'), true), array('controller' => 'reportes', 'action' => 'depreciacion_producto'));?></li>
        <li><?php echo $this->Html->link(__('Mantenciones', true), array('controller' => 'reportes', 'action' => 'mantenciones'));?></li>
        <li><?php echo $this->Html->link(__('Financiamiento', true), array('controller' => 'reportes', 'action' => 'financiamiento'));?></li>
		<li><?php echo $this->Html->link(__('Bienes Muebles', true), array('controller' => 'reportes', 'action' => 'bienes_muebles_slep'));?></li>

		<?php
			if (in_array('logs', $menu)) :
		?>
		<li><?php echo $this->Html->link(__('Ver Log', true), array('controller' => 'logs', 'action' => 'index')); ?></li>
		<?php
			endif;
		?>
	</ul>
	<br />
	<?php
		endif;
	?>
	
	<h3><?php __('Mantenedores'); ?></h3>
	<?php
		if (in_array('proveedores', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Proveedores', true), array('controller' => 'proveedores', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "proveedores") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir Proveedor'), true), array('controller' => 'proveedores', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('colores', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Colores', true), array('controller' => 'colores', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "colores") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir Color'), true), array('controller' => 'colores', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('marcas', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__(utf8_encode('Marcas'), true), array('controller' => 'marcas', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "marcas") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir Marca'), true), array('controller' => 'marcas', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('modelos', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__(utf8_encode('Modelos'), true), array('controller' => 'modelos', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "modelos") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir Modelo'), true), array('controller' => 'modelos', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('financiamientos', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__(utf8_encode('Financiamientos'), true), array('controller' => 'financiamientos', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "financiamientos") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('Nuevo'), true), array('controller' => 'financiamientos', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('cuentas_contables', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Cuentas Contables', true), array('controller' => 'cuentas_contables', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "cuentas_contables") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir Cuenta'), true), array('controller' => 'cuentas_contables', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('situaciones', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Situaciones', true), array('controller' => 'situaciones', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "situaciones") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('Nueva Situaci�n'), true), array('controller' => 'situaciones', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('propiedades', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Propiedades', true), array('controller' => 'propiedades', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "propiedades") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('Nueva Propiedad'), true), array('controller' => 'propiedades', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('responsables', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Responsables', true), array('controller' => 'responsables', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "responsables") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir'), true), array('controller' => 'responsables', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('encargado_dependencias', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Encargado Dependencia', true), array('controller' => 'encargado_dependencias', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "encargado_dependencias") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('Añadir'), true), array('controller' => 'encargado_dependencias', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('encargado_inventarios', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Encargado Inventario', true), array('controller' => 'encargado_inventarios', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "encargado_inventarios") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('Añadir'), true), array('controller' => 'encargado_inventarios', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('encargado_establecimientos', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Director Establecimiento', true), array('controller' => 'encargado_establecimientos', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "encargado_establecimientos") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('Añadir'), true), array('controller' => 'encargado_establecimientos', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('familias', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Familias', true), array('controller' => 'familias', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "familias") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir Familia'), true), array('controller' => 'familias', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('grupos', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Grupos', true), array('controller' => 'grupos', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "grupos") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir Grupo'), true), array('controller' => 'grupos', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('productos', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Productos', true), array('controller' => 'productos', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "productos") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir Producto'), true), array('controller' => 'productos', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('centros_costos', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Centros de Costos', true), array('controller' => 'centros_costos', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "centros_costos") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('Nuevo Centro'), true), array('controller' => 'centros_costos', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('tipos_documentos', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Tipos Documentos', true), array('controller' => 'tipos_documentos', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "tipos_documentos") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('Nuevo Tipo'), true), array('controller' => 'tipos_documentos', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	
	<!--<h3><?php __('Contratos'); ?></h3>-->
	<?php
		if (in_array('contratos', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Contratos', true), array('controller' => 'contratos', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "contratos") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir Contrato'), true), array('controller' => 'contratos', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('tipos_contratos', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Tipos de Contrato', true), array('controller' => 'tipo_contratos', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "tipo_contratos") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir Tipo'), true), array('controller' => 'tipo_contratos', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('tipo_renovaciones', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Tipos de Renovaciones', true), array('controller' => 'tipo_renovaciones', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "tipo_renovaciones") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir Tipo'), true), array('controller' => 'tipo_renovaciones', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('modalidad_compras', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Modalidades de Compra', true), array('controller' => 'modalidad_compras', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "modalidad_compras") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir Modalidad'), true), array('controller' => 'modalidad_compras', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('tipo_montos', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Tipos de Monto', true), array('controller' => 'tipo_montos', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "tipo_montos") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir Tipo'), true), array('controller' => 'tipo_montos', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('unidad_compras', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Unidades de Compra', true), array('controller' => 'unidad_compras', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "unidad_compras") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir Unidad'), true), array('controller' => 'unidad_compras', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('documentos', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Documentos', true), array('controller' => 'documentos', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "documentos") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir Documento'), true), array('controller' => 'documentos', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('rubros', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Rubros Internos', true), array('controller' => 'rubros', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "rubros") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir Rubro'), true), array('controller' => 'rubros', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('bancos', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Bancos', true), array('controller' => 'bancos', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "bancos") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir Banco'), true), array('controller' => 'bancos', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('gastos', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Gastos', true), array('controller' => 'gastos', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "gastos") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir Gasto'), true), array('controller' => 'gastos', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
		<li><?php echo $this->Html->link(__('Resumen de Gastos', true), array('controller' => 'gastos', 'action' => 'resumen')); ?></li>
		<li><?php echo $this->Html->link(__(utf8_encode('Gr�ficos'), true), array('controller' => 'gastos', 'action' => 'graficos')); ?></li>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('evaluaciones', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Evaluaciones', true), array('controller' => 'evaluaciones', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "evaluaciones") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir Evaluaci�n'), true), array('controller' => 'evaluaciones', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
		<?php
			if (in_array('items', $menu)) :
		?>
		<li><?php echo $this->Html->link(__(utf8_encode('Items de Evaluaci�n'), true), array('controller' => 'items', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "items") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir Item'), true), array('controller' => 'items', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
		<?php
			endif;
		?>
		<?php
			if (in_array('tipo_items', $menu)) :
		?>
		<li><?php echo $this->Html->link(__(utf8_encode('Tipos de Item'), true), array('controller' => 'tipo_items', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "tipo_items") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir Tipo'), true), array('tipo_items' => 'items', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
		<?php
			endif;
		?>
		<li><?php echo $this->Html->link(__(utf8_encode('Resumen de Evaluaci�n'), true), array('controller' => 'evaluaciones', 'action' => 'resumen')); ?></li>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('etapas', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Etapas', true), array('controller' => 'etapas', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "etapas") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('Nueva Etapa'), true), array('controller' => 'etapas', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
		<li><?php echo $this->Html->link(__('Resumen de Monitoreo', true), array('controller' => 'etapas', 'action' => 'resumen')); ?></li>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('tipo_cambios', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Tipos de Cambio', true), array('controller' => 'tipo_cambios', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "tipo_cambios") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir Tipo'), true), array('controller' => 'tipo_cambios', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>    
    <?php
		if (in_array('motivos_bajas', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Motivos de Baja', true), array('controller' => 'motivos_bajas', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "motivos_bajas") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir Motivo'), true), array('controller' => 'motivos_bajas', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
    <?php
		if (in_array('dependencias_virtuales', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Dependencias Virtuales', true), array('controller' => 'dependencias_virtuales', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "dependencias_virtuales") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir Dependencia'), true), array('controller' => 'dependencias_virtuales', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<br />
	
	<h3><?php __('Usuarios'); ?></h3>
	<?php
		if (in_array('usuarios', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Usuarios', true), array('controller' => 'usuarios', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "usuarios") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir Usuario'), true), array('controller' => 'usuarios', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('perfiles', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Perfiles', true), array('controller' => 'perfiles', 'action' => 'index')); ?></li>
		<?php
			if ($this->params['controller'] == "perfiles") {
		?>
		<li>
			<ul>
				<li><?php echo $this->Html->link(__(utf8_encode('A�adir Perfil'), true), array('controller' => 'perfiles', 'action' => 'add')); ?></li>
			</ul>
		</li>
		<?php
			}
		?>
	</ul>
	<?php
		endif;
	?>
	<?php
		if (in_array('permisos', $menu)) :
	?>
	<ul>
		<li><?php echo $this->Html->link(__('Permisos', true), array('controller' => 'permisos', 'action' => 'index')); ?></li>
	</ul>
	<?php
		endif;
	?>
	<br />
	<?php
		if (in_array('configuraciones', $menu)) :
	?>
	<h3><?php __('Configuraciones'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Configuraciones', true), array('controller' => 'configuraciones', 'action' => 'index')); ?></li>
	</ul>
	<?php
		endif;
	?>	
</div>
