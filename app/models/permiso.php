<?php
class Permiso extends AppModel {
	var $name = 'Permiso';
	var $primaryKey = 'perm_id';
	var $useTable = 'permisos_perfiles';
	
	var $belongsTo = array(
	 	 'Perfil' => array('className' => 'Perfil',
	 	 	 			   'foreignKey' => 'perf_id')
	);
	
	function guardaTodo($data) {
		$dataSource = $this->getDataSource();
		$dataSource->begin($this);
		
		$sql = "delete from permisos_perfiles";
		$this->query($sql);
		
		foreach ($data as $info) {
			$perf_id = $info['perf_id'];
			$values = array();
			
			if (!isset($info['path'])) {
				continue;
			}
			
			foreach ($info['path'] as $path) {
				$values[] = "(".$perf_id.", '".$path."')";
			}
			
			$sql = "insert into permisos_perfiles (perf_id, pepe_ruta) values ".implode(",", $values);
			$res = $this->query($sql);
			
			if (!is_array($res)) {
				$dataSource->rollback($this);
				return false;
			}
		}
		
		$dataSource->commit($this);
		return true;	
	}
	
	function obtieneTodo() {
		$sql = "select * from permisos_perfiles";
		$res = $this->query($sql);
		$info = array();
		
		foreach ($res as $row) {
			$row = array_pop($row);
			$perf_id = $row['perf_id'];
			$info[$perf_id][] = $row['pepe_ruta'];  
		}
		
		return $info;
	}
	
	function obtieneTodoPorPerfil($perf_id) {
		$sql = "select * from permisos_perfiles where perf_id = ".$perf_id;
		$res = $this->query($sql);
		$info = array();
		
		foreach ($res as $row) {
			$row = array_pop($row);
			$info[] = $row['pepe_ruta'];  
		}
		
		return $info;
	}
	
	function obtieneTodoGlobal($perf_id) {
		$sql = "select * from permisos_perfiles where perf_id = ".$perf_id;
		$res = $this->query($sql);
		$info = array();
		
		foreach ($res as $row) {
			$row = array_pop($row);
			$ruta = dirname($row['pepe_ruta']);
			$ruta = substr($ruta, 1, strlen($ruta));
			
			if (!in_array($ruta, $info)) {
				$info[] = $ruta;
			}
		}
		
		return $info;
	}
	
}

?>