<?php
class Configuracion extends AppModel {
	var $name = 'Configuracion';
	var $useTable = 'configuraciones';
	var $primaryKey = 'conf_id';
	
	var $validate = array(
	    'site_title' => array('rule' => '/[\w\_]+/', 'required' => true, 'message' => 'Debe ingresar el titulo del sitio')
	   ,'site_logo' => array('rule' => array('checkSiteLogo'), 'required' => true, 'message' => 'Debe seleccionar el logo (solo formato PNG, 1MB maximo')
	   ,'mail_from_name' => array('rule' => '/[\w\_]+/', 'required' => true, 'message' => 'Debe ingresar un correo electronico valido')
	   ,'mail_from' => array('rule' => 'email', 'required' => true, 'message' => 'Debe ingresar el nombre del remitente')
	   ,'smtp_host' => array('rule' => array('checkSMTPHost'), 'required' => true, 'message' => 'Debe ingresar el host SMTP')
	   ,'smtp_port' => array('rule' => array('checkSMTPPort'), 'required' => true, 'message' => 'Debe ingresar el puerto SMTP')
	   ,'smtp_timeout' => array('rule' => array('checkSMTPTimeout'), 'required' => true, 'message' => 'Debe ingresar el timeout del servidor SMTP')
	   ,'smtp_user' => array('rule' => array('checkSMTPUser'), 'required' => true, 'message' => 'Debe ingresar el user SMTP')
	   ,'smtp_pass' => array('rule' => array('checkSMTPPass'), 'required' => true, 'message' => 'Debe ingresar la password SMTP')
	   ,'barcode_type' => array('rule' => '/[\w]+/', 'required' => true, 'message' => 'Debe seleccionar el tipo de codigo')
	   ,'barcode_logo' => array('rule' => 'numeric', 'required' => true, 'message' => 'Debe seleccionar si se usa logo o no')
	   ,'barcode_prod' => array('rule' => 'numeric', 'required' => true, 'message' => 'Debe seleccionar si se muestra el producto o no')
	   ,'barcode_date' => array('rule' => 'numeric', 'required' => true, 'message' => 'Debe seleccionar si se usa fecha o no')
	   ,'barcode_cc' => array('rule' => 'numeric', 'required' => true, 'message' => 'Debe seleccionar si se muestra el Centro de Costo')
	   ,'barcode_serie' => array('rule' => 'numeric', 'required' => true, 'message' => 'Debe seleccionar si se muestra el numero de serie')
	   ,'barcode_width' => array('rule' => 'numeric', 'required' => true, 'message' => 'Debe ingresar el ancho del codigo de barra')
	   ,'barcode_height' => array('rule' => 'numeric', 'required' => true, 'message' => 'Debe ingresar el alto del codigo de barra')
	   ,'depr_date_ini' => array('rule' => '/^\d{1,2}\-\d{1,2}$/', 'required' => true, 'message' => 'Debe ingresar la fecha de inicio del periodo, el formato es dd-mm')
	   ,'depr_date_end' => array('rule' => '/^\d{1,2}\-\d{1,2}$/', 'required' => true, 'message' => 'Debe ingresar la fecha de fin del periodo, el formato es dd-mm')
	   ,'param_iva' => array('rule' => '/[\w]+/', 'required' => true, 'message' => 'Debe ingresar el valor del iva')	  
	);
	
	function checkSiteLogo() {
		$site_logo = $this->data['Configuracion']['site_logo'];
		
		if (is_uploaded_file($site_logo['tmp_name'])) {
			// solo png
			$type = $site_logo['type'];
			if ($type != "image/png") {
				return false;
			}
			
			// 1MB max
			$size = $site_logo['size'];
			if ($size >= (1024*1024)) {
				return false;
			}
		}
		
		return true;
	}
	
	function checkSMTPHost() {
		$use_smtp = $this->data['Configuracion']['use_smtp'];
		$smtp_host = $this->data['Configuracion']['smtp_host'];
		
		if ($use_smtp == 1) {
			if (!preg_match("/[\w\s]+/", $smtp_host)) {
				return false;
			}
		}
		return true;
	}
	
	function checkSMTPPort() {
		$use_smtp = $this->data['Configuracion']['use_smtp'];
		$smtp_port = $this->data['Configuracion']['smtp_port'];
		
		if ($use_smtp == 1) {
			if (!preg_match("/[\d]+/", $smtp_port)) {
				return false;
			}
		}
		return true;
	}
	
	function checkSMTPTimeout() {
		$use_smtp = $this->data['Configuracion']['use_smtp'];
		$smtp_timeout = $this->data['Configuracion']['smtp_timeout'];
		
		if ($use_smtp == 1) {
			if (!preg_match("/[\d]+/", $smtp_timeout)) {
				return false;
			}
		}
		return true;
	}
	
	function checkSMTPUser() {
		$smtp_auth = $this->data['Configuracion']['smtp_auth'];
		$smtp_user = $this->data['Configuracion']['smtp_user'];
		
		if ($smtp_auth == 1) {
			if (!preg_match("/[\w\s]+/", $smtp_user)) {
				return false;
			}
		}
		return true;
	}
	
	function checkSMTPPass() {
		$smtp_auth = $this->data['Configuracion']['smtp_auth'];
		$smtp_pass = $this->data['Configuracion']['smtp_pass'];
		
		if ($smtp_auth == 1) {
			if (!preg_match("/[\w\s]+/", $smtp_pass)) {
				return false;
			}
		}
		return true;
	}
	
	function save($data) {
		$values = array();
	
		foreach ($data['Configuracion'] as $key => $val) {
			if ($key == 'site_logo') {
				if ($val['tmp_name'] != "") {
					$fp = fopen($val['tmp_name'], "r");
					$logo = fread($fp, filesize($val['tmp_name']));
					fclose($fp);
					
					$im_logo = new Imagick();
					$im_logo->readImageBlob($logo);
					// crea thumbnail 
					$im_logo->thumbnailImage(80, 110, true);
					$val = base64_encode($im_logo);
				} else {
					// recuperamos el logo anterior
					$val = $this->obtieneLogo();
				}
			}
			
			if ($key == 'depr_date_ini' || $key == 'depr_date_end') {
				list($dia, $mes) = preg_split("/\-/", $val);
				$val = $mes."-".$dia;
			}
			
			$values[] = "('".$key."', '".$val."')";
		}
		
		$sql = "delete from configuraciones";
		$this->query($sql);
		$sql = "insert into configuraciones values ".implode(", ", $values);
		$this->query($sql);
		
		return true;
	}
	
	function obtieneLogo() {
		$sql = "select conf_valor from configuraciones where conf_id = 'site_logo'";
		$res = $this->query($sql);
		return $res[0][0]['conf_valor'];
	}
	
	function obtieneCodBarraConf() {
		$sql = "select *
			    from configuraciones
			    where conf_id in ('barcode_type', 'barcode_logo', 'barcode_prod', 'barcode_date', 'barcode_cc', 'barcode_height', 'barcode_width', 'barcode_serie');";
		$res = $this->query($sql);
		$info = array();
		
		foreach ($res as $row) {
			$row = array_pop($row);
			$info[$row['conf_id']] = $row['conf_valor'];
		}
		
		return $info;
	}
	
	function obtieneSiteConf() {
		$sql = "select *
			    from configuraciones
			    where conf_id in ('site_logo', 'site_title');";
		$res = $this->query($sql);
		$info = array();
		
		foreach ($res as $row) {
			$row = array_pop($row);
			$info[$row['conf_id']] = $row['conf_valor'];
		}
		
		return $info;
	}
}
?>
