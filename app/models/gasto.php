<?php
class Gasto extends AppModel {
	var $name = 'Gasto';
	var $primaryKey = 'gast_id';
	
	 var $belongsTo = array(
	 	 'TipoGasto' => array('className' => 'TipoGasto',
	 	 	 				  'foreignKey' => 'tiga_id')
	    ,'Contrato' => array('className' => 'Contrato',
	 	 	 				 'foreignKey' => 'cont_id')
	 	,'TipoMonto' => array('className' => 'TipoMonto',
	 	 	 				  'foreignKey' => 'timo_id')
	 	 );
	 
	 var $validate = array(
	 	 'gast_monto' => array('rule' => 'numeric', 'required' => false, 'message' => 'El monto debe ser numerico y distinto de vacio')
	 	,'gast_responsable' => array('rule' => '/[\w\s]+/', 'required' => false, 'message' => 'Debe ingresar un responsable')
	 	,'gast_descripcion' => array('rule' => '/[\w\s]+/', 'required' => false, 'message' => 'Debe ingresar una descripcion')
	 	,'timo_id' => array('rule' => array('checkUnique'), 'message' => 'No existe un tipo de cambio para la fecha y el tipo de monto seleccionado. Por favor ingreselo')
	 	,'gast_nro_factura' => array('rule' => '/[\w\s]+/', 'required' => false, 'message' => 'Debe ingresar el numero de factura')
	 	,'cont_nombre' => array('rule' => '/[\w\s]+/', 'required' => false, 'message' => 'Debe seleccionar un contrato')
	 );
	 
	 function checkUnique() {
	 	 $timo_id = $this->data['Gasto']['timo_id'];
	 	 //si el tipo de monto es peso
	 	 if ($timo_id == 4) {
			return true;
		 }
	 	 $tica_fecha = $this->data['Gasto']['gast_fecha'];
	 	 
	 	 //si el tipo de monto es distinto que UF
	 	 if ($timo_id != 1) {
	 	 	 $sql = "select count(*) as cont from tipo_cambio where timo_id = $timo_id and tica_fecha = '$tica_fecha'";
	 	 } else {
	 	 	 $sql = "select count(*) as cont from tipo_cambio where timo_id = $timo_id and to_char(tica_fecha, 'YYYYMM') = '".date("Ym", strtotime($tica_fecha))."'";	
	 	 }
	 	 
	 	 $rs = $this->query($sql);
	 	 $cont = $rs[0][0]['cont'];
		
	 	 if ($cont == 0) {
	 	 	 return false;	
	 	 } else {
	 	 	 return true;	
	 	 }
	 }
	 
	 function getResumen($cont_id) {
	 	 $info_cont = $this->Contrato->find('first'
		 								   ,array('conditions' => array('Contrato.cont_id' => $cont_id)
										   	     ,'recursive'  => 0
												 ,'fields' => array('TipoMonto.timo_descripcion'
												 	 			   ,'Contrato.cont_nombre'
												 	   	   		   ,'Contrato.cont_nro_licitacion'
												 	   	   		   ,'Contrato.cont_monto_compra'
												 	   	   		   ,'Contrato.cont_fecha_inicio'
												 	   	   		   ,'Contrato.cont_fecha_termino')));
		
		$tipo_monto = $info_cont['TipoMonto']['timo_descripcion'];
		$info_cont = $info_cont['Contrato'];
		
		$init = date("Ym01", strtotime($info_cont['cont_fecha_inicio']));
		$end = date("Ym01", strtotime($info_cont['cont_fecha_termino']));
		$months = array();
		
		while ($init <= $end) {
			$months[] = $init;
			$init = date("Ym01", strtotime($init." +1 month"));
		}
	 	 
		$i_month = array();
	 	foreach ($months as $month) {
	 		$i_month[] = "'".date("Ym", strtotime($month))."'";
	 	}
	 	 
	 	$sql = "select gast_fecha
	 				  ,gast_1.total as total_gasto_fijo
	 	 			  ,gast_2.total as total_gasto_variable
	 	 			  ,gast_3.total as total_gasto_presupuestado
	 	 	    from (select distinct to_char(gast_fecha, 'YYYYMM') as gast_fecha
	 	 	   		  from gastos
	 	 	     	  where cont_id = $cont_id
	 	 	     	  and   to_char(gast_fecha, 'YYYYMM') in (".implode(",", $i_month).")
	 	 	     	  order by gast_fecha asc) as table_1
	 	 	    left join (select coalesce(sum(coalesce(gast_monto_convertido, 0 )), 0) as total
                 				  ,to_char(gast_fecha, 'YYYYMM') as gast_fecha
                 		    from gastos
                 		    where tiga_id = 1
                 		    and   cont_id = $cont_id
                 		    group by to_char(gast_fecha, 'YYYYMM')) as gast_1 using (gast_fecha)
                left join (select coalesce(sum(coalesce(gast_monto_convertido, 0)), 0) as total
                				 ,to_char(gast_fecha, 'YYYYMM') as gast_fecha
                 			from gastos
                 			where tiga_id = 2
                 			and   cont_id = $cont_id
                 			group by to_char(gast_fecha, 'YYYYMM')) as gast_2 using (gast_fecha)
                left join (select coalesce(sum(coalesce(gast_monto_convertido, 0)), 0) as total
                				 ,to_char(gast_fecha, 'YYYYMM') as gast_fecha
                 			from gastos
                 			where tiga_id = 3
                 			and   cont_id = $cont_id
                 			group by to_char(gast_fecha, 'YYYYMM')) as gast_3 using (gast_fecha)";
	 	$rs = $this->query($sql);
	 	
	 	if (!$rs) {
	 		return false;	 
	 	} else {
	 		$info_gasto = $rs;
	 		$month_str = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
	 		$info_gasto_ = $info_res = array();
		
	 		foreach ($info_gasto as $gasto) {
	 			$gasto = array_pop($gasto);
	 			$info_gasto_[$gasto['gast_fecha']] = $gasto;
	 		}
		
			$info_gasto = $info_gasto_;
			$keys = array_keys($info_gasto);
		
			foreach ($months as $month) {
				$month = date("Ym", strtotime($month));
			
				if (in_array($month, $keys)) {
					if ($info_gasto[$month]['total_gasto_fijo'] == null) {
						$info_gasto[$month]['total_gasto_fijo'] = 0;
            		
					} elseif ($info_gasto[$month]['total_gasto_variable'] == null) {
						$info_gasto[$month]['total_gasto_variable'] = 0;
            	
					} elseif ($info_gasto[$month]['total_gasto_presupuestado'] == null) {
						$info_gasto[$month]['total_gasto_presupuestado'] = 0;
					}
            	
					$info_gasto[$month]['mes'] = $month_str[date("n", strtotime($month."01"))-1];
					$info_gasto[$month]['anyo'] = date("Y", strtotime($month."01"));
					$info_gasto[$month]['diferencia'] = $info_gasto[$month]['total_gasto_presupuestado']-($info_gasto[$month]['total_gasto_fijo']+$info_gasto[$month]['total_gasto_variable']);
					$info_gasto[$month]['mes_anyo'] = date("m-Y", strtotime($month."01"));
					
					$info_res[] = $info_gasto[$month];
				} else {
					$info_res[] = array('gast_fecha' => $month
									   ,'total_gasto_fijo' => 0
									   ,'total_gasto_variable' => 0
									   ,'total_gasto_presupuestado' => 0
									   ,'mes' => $month_str[date("n", strtotime($month."01"))-1]
									   ,'anyo' => date("Y", strtotime($month."01"))
									   ,'diferencia' => 0
									   ,'mes_anyo' => date("m-Y", strtotime($month."01")));
				}	
			}
		
			//seteamos fechas en formato correcto
			$info_cont['cont_fecha_inicio'] = date("d-m-Y", strtotime($info_cont['cont_fecha_inicio']));
			$info_cont['cont_fecha_termino'] = date("d-m-Y", strtotime($info_cont['cont_fecha_termino']));
			$info_cont['cont_fecha_informe'] = date("d-m-Y");
			$info_cont['timo_descripcion'] = $tipo_monto;
	 	 
			return json_encode(array('res' => 'ok', 'info_cont' => $info_cont, 'info_res' => $info_res)); 
	 	 }
	 }
	 
	 function getFacturasByFecha($fecha, $cont_id) {
	 	$sql = "select Gasto.gast_id
	 				  ,Gasto.gast_nro_factura
	 				  ,Gasto.gast_monto_convertido
	 				  ,Gasto.gast_monto
	 				  ,TipoMonto.timo_descripcion
	 			from gastos as Gasto
	 				,tipo_monto as TipoMonto
	 			where Gasto.timo_id = TipoMonto.timo_id
	 			and   to_char(Gasto.gast_fecha, 'YYYYMM') = '$fecha'
	 			and   Gasto.cont_id = $cont_id
	 			order by Gasto.gast_fecha asc";
	 	$rs = $this->query($sql);
	 	
	 	return $rs;
	 }
	 
	 //busca contratos SOLO si tienen gastos
	 function searchContrato($strings) {
	 	 $arr_strings = array();
	 	 
	 	 foreach ($strings as $string) {
	 	 	 $string = strtolower($string);
			 $string = trim($string);
	 	 	 $arr_strings[] = "lower(cont_nombre) like '%".$string."%' or lower(prov_nombre) like '%".$string."%' or lower(prov_rut) like '%".$string."%'";
	 	 }
	 	 
		 $sql = "select Contrato.cont_id
					   ,Contrato.cont_nombre
					   ,coalesce(regs.total_regs, 0) as total_regs
				 from contratos as Contrato
				 natural join proveedores as Proveedor
				 left join (select cont_id
								  ,count(*) as total_regs
						    from gastos
							group by cont_id) as regs using (cont_id)
				 where total_regs > 0
				 and   (".implode("or ", $arr_strings).")";
				 
	 	 $rs = $this->query($sql);
	 	 return $rs;
	 }
	 
	 function updMontoConvertido($gast_id, $gast_monto, $timo_id, $gast_fecha) {
		$gast_fecha = $gast_fecha['year'].$gast_fecha['month'].$gast_fecha['day'];
		$sql = "update gastos
				set gast_monto_convertido = convertir_moneda(".$gast_monto.", ".$timo_id.", '".$gast_fecha."')
				where gast_id = ".$gast_id;
		$rs = $this->query($sql);

		return true;
	 }
}
?>