<?php
class Reporte extends AppModel {
	var $name = 'Reporte';
	var $useTable = false;
	
	function stock($conds) {
		$and = "";
		
		// existencias
		if (isset($conds['conditions']['prod_id']) && $conds['conditions']['tibi_id'] == 3) {
			$and .= "and Producto.prod_id = ".$conds['conditions']['prod_id'];
		}
		
		// activos_fijos
		if (isset($conds['conditions']['prod_id']) && $conds['conditions']['tibi_id'] == 1) {
			$and .= "and prod.prod_id = ".$conds['conditions']['prod_id'];
		}
		
		$ceco_id = $conds['conditions']['ceco_id'];
		
		if ($conds['conditions']['tibi_id'] == 3) { // solo existencias
			$sql = "select Producto.prod_id
						  ,Producto.prod_nombre
						  ,Producto.prod_codigo
						  ,TipoBien.tibi_nombre
						  ,Familia.fami_nombre
						  ,Grupo.grup_nombre
						  ,coalesce(total_entradas.total, 0) as total_entradas
						  ,coalesce(total_traslados.total, 0) as total_traslados
						  ,StockCentroCosto.stcc_stock_critico
					from detalle_existencias as DetalleExistencia
					join productos as Producto using (prod_id)
					join tipos_bienes as TipoBien using (tibi_id)
					join existencias as Existencia using (exis_id)
					join grupos as Grupo using (grup_id)
					join familias as Familia using (fami_id)
					join centros_costos as CentroCosto using (ceco_id)
					left join (select deex.prod_id 
									 ,sum(deex.deex_cantidad) as total
							   from detalle_existencias as deex
							   join existencias as exis using (exis_id) 
							   where exis.tmov_id = 1
							   and   exis.esre_id = 1
							   and   exis.ceco_id in (".implode(",", $ceco_id).")
							   group by deex.prod_id) as total_entradas using (prod_id)
					left join (select deex.prod_id 
									 ,sum(deex.deex_cantidad) as total
							   from detalle_existencias as deex
							   join existencias as exis using (exis_id) 
							   where exis.tmov_id = 2
							   and   exis.esre_id = 1
							   and   exis.ceco_id in (".implode(",", $ceco_id).")
							   group by deex.prod_id) as total_traslados using (prod_id)
					left join (select prod_id
									 ,stcc_stock_critico
							   from stocks_cc where ceco_id in (".implode(",", $ceco_id).")) as StockCentroCosto using (prod_id)
					where Existencia.ceco_id in (".implode(",", $ceco_id).")
					and   Existencia.tmov_id = 1
					and   Existencia.esre_id = 1
					and   TipoBien.tibi_id   = 3
					".$and."
					group by Producto.prod_id
							,Producto.prod_nombre
							,Producto.prod_codigo
							,TipoBien.tibi_nombre
							,Familia.fami_nombre
							,Grupo.grup_nombre
							,total_entradas.total
							,total_traslados.total
							,StockCentroCosto.stcc_stock_critico
					order by Producto.prod_nombre, Producto.prod_id";
					
		} elseif ($conds['conditions']['tibi_id'] == 1) { // solo activos fijos
			$sql = "select ubaf.ceco_id
						  ,ubaf.ubaf_codigo
						  ,ubaf.ubaf_fecha_garantia
						  ,ubaf.ubaf_depreciable
						  ,ubaf.ubaf_vida_util
						  ,ubaf.ubaf_serie
						  ,prod.prod_id
						  ,prod.prod_nombre
						  ,tibi.tibi_nombre					  
						  ,fami.fami_nombre					  
						  ,grup.grup_nombre					  
						  ,ubaf.ubaf_precio					  					  
						  ,prop.prop_nombre					  
						  ,situ.situ_nombre					  
						  ,marc.marc_nombre					  
						  ,colo.colo_nombre					  
						  ,mode.mode_nombre					  
						  ,ubaf.ubaf_serie					  
						  ,hijo.ceco_nombre as hijo
						  ,padre.ceco_nombre as padre
						  ,abuelo.ceco_nombre as abuelo					  
					from ubicaciones_activos_fijos as ubaf
					join productos as prod using (prod_id)
					join propiedades as prop using (prop_id)
					join situaciones as situ using (situ_id)
					left join centros_costos as hijo on (hijo.ceco_id = ubaf.ceco_id)
					left join centros_costos as padre on (hijo.ceco_id_padre = padre.ceco_id)
					left join centros_costos as abuelo on (padre.ceco_id_padre = abuelo.ceco_id)
					left join marcas as marc using (marc_id)
					left join colores as colo using (colo_id)
					left join modelos as mode using (mode_id)
					join grupos as grup using (grup_id)
					join familias as fami using (fami_id)
					join tipos_bienes as tibi using (tibi_id)
					where ubaf.ceco_id in (".implode(",", $ceco_id).")
					".$and."							
					order by prod.prod_nombre, 
						     ubaf.ubaf_codigo asc;";
		}		
		
		$rs = $this->query($sql);
		return $rs;
	}
	
	function existencias($conds) {
		$and = "";
	
		if (isset($conds['conditions']['prod_id'])) {
			$and .= "and prod.prod_id = ".$conds['conditions']['prod_id'];
		}
		
		$ceco_id = $conds['conditions']['ceco_id'];
		
		$sql = "select prod.prod_id
					  ,prod.prod_nombre
					  ,prod.prod_codigo
					  ,tibi.tibi_nombre
					  ,fami.fami_nombre
					  ,grup.grup_nombre
					  ,coalesce(stcc.stcc_stock_critico, 0) as stcc_stock_critico
					  ,deex.deex_serie
					  ,deex.deex_fecha_vencimiento
					  ,deex.deex_precio
					  ,(total_entradas_exis(".$ceco_id.", prod.prod_id, deex.deex_serie, deex.deex_fecha_vencimiento, deex.deex_precio)
					   - total_traslados_exis(".$ceco_id.", prod.prod_id, deex.deex_serie, deex.deex_fecha_vencimiento, deex.deex_precio)) as total_stock
				from detalle_existencias as deex
				join productos as prod using (prod_id)
				join existencias as exis using (exis_id)
				join tipos_bienes as tibi using (tibi_id)
				join grupos as grup using (grup_id)
				join familias as fami using (fami_id)
				left join stocks_cc as stcc on (exis.ceco_id = stcc.ceco_id and prod.prod_id = stcc.prod_id)
				where exis.esre_id = 1
				and   exis.ceco_id = ".$ceco_id."
				".$and."
				group by prod.prod_id
						,prod.prod_nombre
						,prod.prod_codigo
						,tibi.tibi_nombre
						,fami.fami_nombre
						,grup.grup_nombre
						,stcc.stcc_stock_critico
						,deex.deex_serie
						,deex.deex_fecha_vencimiento
						,deex.deex_precio
				order by prod.prod_nombre
						,deex.deex_fecha_vencimiento ASC";
		
		$res = $this->query($sql);
		return $res;
	}
	
	function activos_fijos($conds) {
		$and = "";
	
		if (isset($conds['conditions']['prod_id'])) {
			$and .= "and prod.prod_id = ".$conds['conditions']['prod_id'];
		}
		
		$ceco_id = $conds['conditions']['ceco_id'];
		
		$sql = "select ubaf.ceco_id
					  ,prod.prod_nombre
					  ,tibi.tibi_nombre
					  ,fami.fami_nombre
					  ,grup.grup_nombre
					  ,ubaf.ubaf_precio
					  ,prop.prop_nombre
					  ,situ.situ_nombre
					  ,marc.marc_nombre
					  ,colo.colo_nombre
					  ,mode.mode_nombre
					  ,ubaf.ubaf_serie
					  ,count(*) as total
					  ,hijo.ceco_nombre as hijo
					  ,padre.ceco_nombre as padre
					  ,abuelo.ceco_nombre as abuelo
				from ubicaciones_activos_fijos as ubaf
				join productos as prod using (prod_id)
				join propiedades as prop using (prop_id)
				join situaciones as situ using (situ_id)
				left join centros_costos as hijo on (hijo.ceco_id = ubaf.ceco_id)
				left join centros_costos as padre on (hijo.ceco_id_padre = padre.ceco_id)
				left join centros_costos as abuelo on (padre.ceco_id_padre = abuelo.ceco_id)
				left join marcas as marc using (marc_id)
				left join colores as colo using (colo_id)
				left join modelos as mode using (mode_id)
				join grupos as grup using (grup_id)
				join familias as fami using (fami_id)
				join tipos_bienes as tibi using (tibi_id)
				where ubaf.ceco_id in (".implode(",", $ceco_id).")
				".$and."				
				group by ubaf.ceco_id
					,prod.prod_nombre
					,tibi.tibi_nombre
					,fami.fami_nombre
					,grup.grup_nombre
					,ubaf.ubaf_precio
					,prop.prop_nombre
					,situ.situ_nombre
					,marc.marc_nombre
					,colo.colo_nombre
					,mode.mode_nombre
					,ubaf.ubaf_serie
					,hijo.ceco_nombre
					,padre.ceco_nombre
					,abuelo.ceco_nombre
				order by prod.prod_nombre asc ";
		
		$res = $this->query($sql);
		return $res;
	}
	
	function activosFijosGeneral($ceco_id) {
	
		$sql = "select ceco.ceco_id
		              ,ceco.ceco_nombre
				      ,prod.prod_nombre
					  ,tibi.tibi_nombre
					  ,fami.fami_nombre
					  ,grup.grup_nombre
					  ,ubaf.ubaf_precio
					  ,prop.prop_nombre
					  ,situ.situ_nombre
					  ,marc.marc_nombre
					  ,colo.colo_nombre
					  ,mode.mode_nombre
					  ,ubaf.ubaf_serie
					  ,count(*) as total
					  ,depe.ceco_nombre as dependencia
					  ,esta.ceco_nombre as establecimiento
					  ,ubaf.ubaf_fecha_adquisicion
				from ubicaciones_activos_fijos as ubaf
				left join centros_costos as ceco using (ceco_id)
				left join productos as prod using (prod_id)
				left join propiedades as prop using (prop_id)
				left join situaciones as situ using (situ_id)
				left join marcas as marc using (marc_id)
				left join colores as colo using (colo_id)
				left join modelos as mode using (mode_id)
				left join grupos as grup using (grup_id)
				left join familias as fami using (fami_id)
				left join tipos_bienes as tibi using (tibi_id)
				left join centros_costos as depe on (ceco.ceco_id_padre = depe.ceco_id)
				left join centros_costos as esta on (depe.ceco_id_padre = esta.ceco_id)
				where tibi.tibi_id = 1
				and ceco.ceco_id in (".implode(",", $ceco_id).")		
				group by ceco.ceco_id
						,ceco.ceco_nombre
						,prod.prod_nombre
						,tibi.tibi_nombre
						,fami.fami_nombre
						,grup.grup_nombre
						,ubaf.ubaf_precio
						,prop.prop_nombre
						,situ.situ_nombre
						,marc.marc_nombre
						,colo.colo_nombre
						,mode.mode_nombre
						,ubaf.ubaf_serie
						,depe.ceco_nombre
						,esta.ceco_nombre
						,ubaf.ubaf_fecha_adquisicion
				order by ceco.ceco_nombre
						,prod.prod_nombre asc";
		$res = $this->query($sql);
		return $res;		
	}

	function activosFijosGeneralMigracion($ceco_id) {
		$sql = "select ceco.ceco_id
		              ,ceco.ceco_nombre
				      ,prod.prod_nombre
					  ,tibi.tibi_nombre
					  ,fami.fami_nombre
					  ,grup.grup_nombre
					  ,ubaf.ubaf_precio
					  ,prop.prop_nombre
					  ,situ.situ_nombre
					  ,marc.marc_nombre
					  ,colo.colo_nombre
					  ,mode.mode_nombre
					  ,ubaf.ubaf_serie
					  ,ubaf.ubaf_codigo
					  ,ubaf.ubaf_vida_util
					  ,depe.ceco_nombre as dependencia
					  ,esta.ceco_nombre as establecimiento
				from ubicaciones_activos_fijos as ubaf
				left join centros_costos as ceco on (ceco.ceco_id = ubaf.ceco_id) 
				left join productos as prod on (prod.prod_id = ubaf.prod_id)
				left join propiedades as prop on (prop.prop_id = ubaf.prop_id)
				left join situaciones as situ on (situ.situ_id = ubaf.situ_id)
				left join marcas as marc on (marc.marc_id = ubaf.marc_id)
				left join colores as colo on (colo.colo_id = ubaf.colo_id)
				left join modelos as mode on (mode.mode_id = ubaf.mode_id)
				left join grupos as grup using (grup_id)
				left join familias as fami using (fami_id)
				left join tipos_bienes as tibi using (tibi_id)
				left join centros_costos as depe on (ceco.ceco_id_padre = depe.ceco_id)
				left join centros_costos as esta on (depe.ceco_id_padre = esta.ceco_id)
				where tibi.tibi_id = 1
				and ceco.ceco_id in (".implode(",", $ceco_id).")
				order by ceco.ceco_nombre
						,prod.prod_nombre asc";
		$res = $this->query($sql);
		return $res;
	}

	function findDetalleActivo($ubaf_codigo = null) {
		$sql = "select deaf_codigo
					,activo_fijo.acfi_orden_compra
					,activo_fijo.acfi_nro_documento
					,activo_fijo.acfi_fecha_documento
					,prov.prov_nombre as nombre_proveedor
					,prov.prov_rut as rut_proveedor
					,detalle.deaf_fecha_adquisicion
				from detalle_activos_fijos as detalle
				inner join activos_fijos as activo_fijo on (activo_fijo.acfi_id = detalle.acfi_id)
				left join proveedores as prov on (prov.prov_id = activo_fijo.prov_id)
				where deaf_codigo = '".$ubaf_codigo."'
				order by activo_fijo.acfi_fecha asc
				limit 1;";
		$res = $this->query($sql);
		return $res;
	}
	
	function TrasladosPorFecha($ceco_id, $ceco_id_hijo = null, $tibi_id, $fecha_desde, $fecha_hasta) {
		
		list($dia, $mes, $ano) = preg_split("/-/", $fecha_desde);
		$fecha_desde = $ano.$mes.$dia;
		
		list($dia, $mes, $ano) = preg_split("/-/", $fecha_hasta);
		$fecha_hasta = $ano.$mes.$dia;	

		$and = "";
	
		if ($ceco_id_hijo != "null") {
			$and .= "and ceco2.ceco_id = ".$ceco_id_hijo;
		}
		
		if ($tibi_id == 1) { //Solo Activo Fijo
			$sql = "select deaf.deaf_codigo
							,prod.prod_nombre
							,tibi.tibi_nombre
							,ceco.ceco_nombre
							,ceco2.ceco_nombre
							,acfi.tmov_id
							,acfi.esre_id
							,acfi.acfi_fecha
							,marc.marc_nombre
							,prop.prop_nombre
							,colo.colo_nombre
							,situ.situ_nombre
							,mode.mode_nombre
							,deaf.deaf_fecha_garantia
							,deaf.deaf_fecha_adquisicion
							,deaf.deaf_serie
					from detalle_activos_fijos as deaf
					left join productos as prod using (prod_id)
					left join marcas as marc using (marc_id)
					left join propiedades as prop using (prop_id) 
					left join colores as colo using (colo_id)
					left join situaciones as situ using (situ_id)
					left join modelos as mode using (mode_id)
					left join activos_fijos as acfi using (acfi_id)
					left join centros_costos as ceco using (ceco_id)
					left join centros_costos as ceco2 on (acfi.ceco_id_hijo = ceco2.ceco_id)
					left join tipos_bienes as tibi using (tibi_id)
					where acfi.tmov_id = 2
					and acfi.esre_id = 1
                    and acfi.ceco_id = ".$ceco_id."
		    		".$and."
                    and acfi.acfi_fecha >= '".$fecha_desde." 00:00:00'
                    and acfi.acfi_fecha <= '".$fecha_hasta." 23:59:59'
                    order by acfi.ceco_id_hijo
			     			 ,prod.prod_nombre
							 ,acfi.acfi_fecha asc";
							 
		} elseif ($tibi_id == 3) { //Solo Existencias
			$sql = "select prod.prod_nombre
							,ceco.ceco_nombre
							,ceco2.ceco_nombre
							,tibi.tibi_nombre
							,exis.exis_fecha
							,deex.deex_precio
							,deex.deex_serie
							,deex.deex_fecha_vencimiento
							,deex.deex_cantidad
					from detalle_existencias as deex
					left join productos as prod using (prod_id)
					left join tipos_bienes as tibi using (tibi_id)
					left join existencias as exis using (exis_id)
					left join centros_costos as ceco using (ceco_id)
					left join centros_costos as ceco2 on (exis.ceco_id_hijo = ceco2.ceco_id)
					where exis.esre_id = 1
					and exis.tmov_id = 2
					and exis.ceco_id = ".$ceco_id."
					".$and."
					and exis.exis_fecha >= '".$fecha_desde." 00:00:00'
					and exis.exis_fecha <= '".$fecha_hasta." 23:59:59'
					group by prod.prod_nombre
							,ceco.ceco_nombre
							,ceco2.ceco_nombre
							,tibi.tibi_nombre
							,exis.exis_fecha
							,deex.deex_precio
							,deex.deex_serie
							,deex.deex_fecha_vencimiento
							,deex.deex_cantidad
					order by ceco2.ceco_nombre
						,prod.prod_nombre
						,exis.exis_fecha asc";
		}
		
		$res = $this->query($sql);
		return $res;
	}
	
	function stockCentroCosto($ccs) {
		$sql = "select ceco.ceco_nombre
					   ,prod.prod_nombre
					   ,count(*) as total
				from detalle_activos_fijos
				left join activos_fijos as acfi using (acfi_id)
				left join productos as prod using (prod_id)
				left join centros_costos as ceco using (ceco_id)
				where ceco.ceco_id in (".implode(",", $ccs).")
				group by prod.prod_nombre
					    ,ceco.ceco_nombre 
				order by ceco.ceco_nombre
					    ,prod.prod_nombre asc";
						
		$res = $this->query($sql);
		return $res;
	}
	
	function stockCentroCostoGeneral($ccs) {
		
		$sql = "select prod.prod_nombre
					  ,count(*) as total
				from detalle_activos_fijos
				left join activos_fijos as acfi using (acfi_id)
				left join productos as prod using (prod_id)
				left join centros_costos as ceco using (ceco_id)
				where ceco.ceco_id in (".implode(",", $ccs).")
				group by prod.prod_nombre 
				order by prod.prod_nombre asc";
				
		$res = $this->query($sql);
		return $res;
	}
	
	function bajasCentrosCostos($ccs, $prod_id, $fecha_desde, $fecha_hasta) {
		$and = "";
		
		// existencias
		if ($prod_id != 'null') {
			$and .= "and prod.prod_id = ".$prod_id;
		}
		
		if ($fecha_desde != 'null') {
			$and .= "and baaf.baaf_fecha between '".$fecha_desde." 00:00:00' and '".$fecha_hasta." 23:59:59'";
		}
		
		$sql = "select baaf.baaf_fecha,
					   baaf.baaf_numero_documento,
					   devi.devi_nombre,
					   moba.moba_nombre,
					   prod.prod_nombre,
					   deba.deba_codigo,
					   deba.deba_precio,
					   deba.deba_depreciable,
					   deba.deba_vida_util,
					   ceco_hijo.ceco_nombre as unidad,
					   ceco_padre.ceco_nombre as dependencia,
					   ceco_abuelo.ceco_nombre as establecimiento,
					   baaf.baaf_observacion					   
				from bajas_activos_fijos as baaf
				inner join detalle_bajas as deba using (baaf_id)
				left join centros_costos as ceco_hijo on (baaf.ceco_id = ceco_hijo.ceco_id)
				left join centros_costos as ceco_padre on (ceco_hijo.ceco_id_padre = ceco_padre.ceco_id)
				left join centros_costos as ceco_abuelo on (ceco_padre.ceco_id_padre = ceco_abuelo.ceco_id)
				inner join dependencias_virtuales as devi using (devi_id)
				inner join motivos_bajas as moba using (moba_id)
				inner join productos as prod using (prod_id)
				where ceco_hijo.ceco_id in (".implode(",", $ccs).")
				".$and."
				order by prod.prod_nombre asc";
				
		$res = $this->query($sql);
		return $res;
	}
	
	function reporte_financiamiento($fina_id) {
		$sql = "select acfi.acfi_fecha
					  ,acfi.acfi_fecha_documento
					  ,deaf.deaf_codigo
					  ,prod.prod_nombre
					  ,colo.colo_nombre
					  ,marc.marc_nombre
					  ,prop.prop_nombre
					  ,mod.mode_nombre
					  ,situ.situ_nombre
					  ,fina.fina_nombre
					  ,hijo.ceco_nombre as hijo
					  ,padre.ceco_nombre as padre
					  ,abuelo.ceco_nombre as abuelo
					  ,deaf.deaf_precio
					  ,deaf.deaf_depreciable
					  ,deaf.deaf_vida_util
					  ,deaf.deaf_fecha_garantia
					  ,deaf.deaf_fecha_adquisicion
					  ,deaf.deaf_serie
				from activos_fijos as acfi
				inner join detalle_activos_fijos as deaf using (acfi_id)
				inner join productos as prod using (prod_id)
				left join colores as colo using (colo_id)
				left join marcas as marc using (marc_id)
				left join modelos as mod using (mode_id)
				inner join propiedades as prop using (prop_id)
				inner join situaciones as situ using (situ_id)
				left join centros_costos as hijo on (hijo.ceco_id = acfi.ceco_id)
				left join centros_costos as padre on (padre.ceco_id = hijo.ceco_id_padre)
				left join centros_costos as abuelo on (abuelo.ceco_id = padre.ceco_id_padre)
				inner join financiamientos as fina using (fina_id)
				where fina.fina_id = ".$fina_id."
				order by prod.prod_nombre";
		$res = $this->query($sql);
		return $res;	
	}

	function bienesMueblesSlep($ceco_id)
	{
		$sql = "select ceco.ceco_id
		              ,ceco.ceco_nombre
				      ,prod.prod_nombre
				      ,prod.prod_id
					  ,situ.situ_nombre
					  ,situ.situ_id
					  ,ubaf.ubaf_fecha_adquisicion
					  ,count(*) as total
					  ,nied.nied_nombre
					  ,fina.fina_nombre
					  ,MAX(acfi.acfi_id) AS acfi_id
				from ubicaciones_activos_fijos as ubaf
				left join centros_costos as ceco using (ceco_id)
				left join niveles_educativos as nied using (nied_id)
				left join productos as prod using (prod_id)
				left join situaciones as situ using (situ_id)
				left join tipos_bienes as tibi using (tibi_id)
				inner join detalle_activos_fijos as deaf on (deaf.deaf_codigo = ubaf.ubaf_codigo)
				inner join activos_fijos as acfi on (acfi.acfi_id = deaf.acfi_id)
				inner join financiamientos as fina using (fina_id)
				where tibi.tibi_id = 1
				and ceco.ceco_id in (".implode(",", $ceco_id).")
				group by ceco.ceco_id
						,ceco.ceco_nombre
						,prod.prod_nombre
						,prod.prod_id
						,situ.situ_nombre
						,situ.situ_id
						,ubaf.ubaf_fecha_adquisicion
						,nied.nied_nombre
						,fina.fina_nombre
				order by ceco.ceco_nombre
						,prod.prod_nombre asc;";
		$res = $this->query($sql);
		return $res;		
	}
}	
?>
