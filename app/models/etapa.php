<?php
class Etapa extends AppModel {
	var $name = 'Etapa';
	var $primaryKey = 'etap_id';

	var $belongsTo = array(
		'Contrato' => array(
			'className' => 'Contrato',
			'foreignKey' => 'cont_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	var $hasMany = array(
		'Actividad' => array('className' => 'Actividad',
						     'foreignKey' => 'etap_id',
						     'order' => 'Actividad.acti_id')
	);
	
	var $validate = array(
		//'cont_id' => array('rule' => 'numeric', 'required' => true, 'message' => 'Debe seleccionar un contrato')
	    'cont_nombre' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar el nombre del contrato')
	   ,'etap_nombre' => array('rule' => '/[\w\s\-\_]+/', 'required' => true, 'message' => 'Debe ingresar el nombre de la etapa')
	);
	
	function getResumen($cont_id) {
		 $info_cont = $this->Contrato->find('first'
		 								   ,array('conditions' => array('Contrato.cont_id' => $cont_id)
										   	     ,'recursive'  => 0
										   	     ,'fields' => array('Proveedor.prov_nombre'
												 	 			   ,'Contrato.cont_nombre')
												 ));
		 $info_etapa = $this->find('all', array('conditions' => array('Contrato.cont_id' => $cont_id)
		 	 								   ,'recursive' => 1
		 	 								   ,'order' => 'Etapa.etap_id'
		 	 								   ));
		 $info_cont = array('cont_nombre' => $info_cont['Contrato']['cont_nombre']
		 	 			   ,'prov_nombre' => $info_cont['Proveedor']['prov_nombre']);
		 $info_res = array();
		 
		 foreach ($info_etapa as $data) {
		 	 $arr_acti = array();
		 	 
		 	 foreach ($data['Actividad'] as $acti) {
		 	 	 if ($acti['acti_fecha_real'] != "" || $acti['acti_fecha_presupuestada'] != "") {
					 $fecha_real_stamp = strtotime($acti['acti_fecha_real']);
					 $fecha_presupuestada_stamp = strtotime($acti['acti_fecha_presupuestada']);
					 $acti['diferencia'] = round(($fecha_presupuestada_stamp-$fecha_real_stamp)/86400); 
					 $acti['acti_fecha_presupuestada'] = date("d-m-Y", strtotime($acti['acti_fecha_presupuestada']));
					 $acti['acti_fecha_real'] = date("d-m-Y", strtotime($acti['acti_fecha_real']));
					 
					 if ($fecha_real_stamp > $fecha_presupuestada_stamp) {
						 $acti['cumple'] = "NO";  
					 } else {
						 $acti['cumple'] = "SI";  
					 }
		 	 	 } else {
		 	 	 	$acti['acti_fecha_real'] = "";
		 	 	 	$acti['acti_fecha_presupuestada'] = "";
		 	 	 	$acti['cumple'] = "";
		 	 	 	$acti['diferencia'] = 0;
		 	 	 }
		 	 	 
		 	 	 $arr_acti[] = $acti;
		 	 }
		 	 $data['Actividad'] = $arr_acti;
		 
		 	 $info_res[] = array('etap_nombre' => $data['Etapa']['etap_nombre'], 'info_act' => $data['Actividad']);
		 }
		 
		 return json_encode(array('info_cont' => $info_cont, 'info_res' => $info_res));
	}
	
	 //busca contratos SOLO si tienen etapas
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
                            from etapas
                            group by cont_id) as regs using (cont_id)
				 where total_regs > 0
				 and   (".implode("or ", $arr_strings).")";
				 
	 	 $rs = $this->query($sql);
	 	 return $rs;
	 }
}
?>