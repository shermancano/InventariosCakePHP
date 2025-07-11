<?php
class CentroCosto extends AppModel {
	var $name = 'CentroCosto';
	var $useTable = 'centros_costos';
	var $primaryKey = 'ceco_id';
	
	var $belongsTo = array(
	 	 'Comuna' => array('className' => 'Comuna',
	 	 	 			   'foreignKey' => 'comu_id')
	 	,'CentroCosto2' => array('className' => 'CentroCosto',
	 	 	 			   	     'foreignKey' => 'ceco_id_padre')
		,'TipoLocalizacion' => array('className' => 'TipoLocalizacion',
										'foreignKey' => 'tilo_id')
	);
	
	var $validate = array(
		'comu_id' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe seleccionar la comuna')
	   ,'ceco_nombre' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar el nombre del Centro de Costo')
	   ,'ceco_direccion' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar la direccion del Centro de Costo')
	);
	
	function hasChildren($ceco_id_padre) {
		$sql = "select count(*) as total from centros_costos where ceco_id_padre = ".$ceco_id_padre;
		$res = $this->query($sql);
		
		if ($res[0][0]['total'] == 0) {
			return false;
		} else {
			return true;
		}
	}
	
	function findAllChildren($ceco_id, $is_child = false) {
		if ($is_child == true) {
			$sql = "select *
					from centros_costos
					where ceco_id_padre = ".$ceco_id."
					order by ceco_nombre";
		} else {
			$sql = "select *
					from centros_costos
					where ceco_id = ".$ceco_id."
					order by ceco_nombre";
		}
		
		$res = $this->query($sql);
		$count = 0;
		
		if (sizeof($res) == 0) {
			return;
		}
		
		foreach ($res as $cc) {
			$cc = array_pop($cc);
			$childs[$count]['CentroCosto'] = $cc;
			
			if ($this->hasChildren($cc['ceco_id'])) {
				$childs[$count]['children'] = $this->findAllChildren($cc['ceco_id'], true);
			}
			
			$count++;
		}
		return $childs;
	}
	
	function findAll() {
		$sql = "select ceco_id from centros_costos where ceco_id_padre is null limit 1";
		$res = $this->query($sql);
		return $this->findAllChildren($res[0][0]['ceco_id']);
	}
	
	function findUbicacion($ceco_id) {
		$sql = "select * from centros_costos where ceco_id = ".$ceco_id;
		$res = $this->query($sql);
	
		if (sizeof($res) == 0) {
			return null;
		}
		
		$row = array_pop(array_pop($res));
		$ret = $row['ceco_nombre'];
		
		if ($row['ceco_id_padre'] != "") {
			$ret = $this->findUbicacion($row['ceco_id_padre'])." / ".$ret;
		}
		return $ret;
	}
	
	function buscaResponsable($ceco_id) {
		$sql = "select usua_nombre
					  ,ceco_id_padre
				from centros_costos as CentroCosto
				left join responsables as Responsable using (ceco_id)
				left join usuarios as Usuario using (usua_id)
				where ceco_id = ".$ceco_id;
				
		$res = $this->query($sql);
		$ret = "";
		
		foreach ($res as $row) {
			$row = array_pop($row);
			
			if ($row['usua_nombre'] == "") {
				$ret .= $this->buscaResponsable($row['ceco_id_padre']);
			} else {
				$ret .= $row['usua_nombre'].",";
			}
			
		}
		
		return $ret;		
	}

	function buscaEncargadoDependencia($ceco_id) {
		$sql = "select usua_nombre
					  ,ceco_id_padre
				from centros_costos as CentroCosto
				left join encargado_dependencias as Responsable using (ceco_id)
				left join usuarios as Usuario using (usua_id)
				where ceco_id = ".$ceco_id;

		$res = $this->query($sql);
		$ret = "";

		foreach ($res as $row) {
			$row = array_pop($row);

			if ($row['usua_nombre'] == "") {
				if ($row['ceco_id_padre'] != "") {
					$ret .= $this->buscaEncargadoDependencia($row['ceco_id_padre']);
				}
			} else {
				$ret .= $row['usua_nombre'].",";
			}
		}

		return $ret;
	}

	function buscaEncargadoInventario($ceco_id) {
		$sql = "select usua_nombre
					  ,ceco_id_padre
				from centros_costos as CentroCosto
				left join encargado_inventarios as Responsable using (ceco_id)
				left join usuarios as Usuario using (usua_id)
				where ceco_id = ".$ceco_id;
				
		$res = $this->query($sql);
		$ret = "";

		foreach ($res as $row) {
			$row = array_pop($row);
			if ($row['usua_nombre'] == "") {
				if ($row['ceco_id_padre'] != "") {
					$ret .= $this->buscaEncargadoInventario($row['ceco_id_padre']);
				}
			} else {
				$ret .= $row['usua_nombre'].",";
			}			

		}

		return $ret;
	}

	function buscaEncargadoEstablecimiento($ceco_id) {
		$sql = "select usua_nombre
					  ,ceco_id_padre
				from centros_costos as CentroCosto
				left join encargado_establecimientos as Responsable using (ceco_id)
				left join usuarios as Usuario using (usua_id)
				where ceco_id = ".$ceco_id;


		$res = $this->query($sql);
		$ret = "";
		
		foreach ($res as $row) {
			$row = array_pop($row);

			if ($row['usua_nombre'] == "") {
				if ($row['ceco_id_padre'] != "") {
					$ret .= $this->buscaEncargadoEstablecimiento($row['ceco_id_padre']);
				}
			} else {
				$ret .= $row['usua_nombre'].",";
			}

		}
		
		return $ret;		
	}
	
	function findCentroCostoPadrePaint($ceco_id) {
		$sql = "select hijo.ceco_id,
					   hijo.ceco_nombre
				from centros_costos as padre
				left join centros_costos as hijo on (padre.ceco_id = hijo.ceco_id_padre)
				where padre.ceco_id = (select ceco_id
									   from centros_costos
									   where ceco_id = ".$ceco_id.")
				order by hijo.ceco_nombre asc";
		$res = $this->query($sql);
		return $res;
	}
}
?>
