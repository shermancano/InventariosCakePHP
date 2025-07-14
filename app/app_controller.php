<?php

require_once("vendors/Spreadsheet/Excel/Writer.php");
require_once("vendors/Image/Barcode2.php");
require_once("vendors/html2pdf/HTML_ToPDF.php");
require_once("vendors/fpdf/fpdf.php");
require_once("vendors/dompdf/dompdf_config.inc.php");

class AppController extends Controller {
	var $components = array('Session', 'Email');
    var $helpers = array('Html', 'Form', 'Session');
	var $uses = array('Configuracion', 'Log');
	
    public function __construct() {
		parent::__construct();	
	}
	
	public function beforeFilter() {
		if (!$this->Session->check('userdata')
			&& $this->here != '/'
			&& $this->here != '/login/index'
			&& !preg_match('/\/gastos\/pdf_html\/[\d]+/', $this->here)
			&& !preg_match('/\/evaluaciones\/pdf_html\/[\d]+/', $this->here)
			&& !preg_match('/\/etapas\/pdf_html\/[\d]+/', $this->here)
			&& !preg_match('/\/existencias\/comprobante_entrada_pdf\/[\d]+/', $this->here)
			&& !preg_match('/\/ordenes_compras\/comprobante_pdf\/[\d]+/', $this->here)
			&& !preg_match('/\/reportes\/stock_pdf_html\/[\d]+/', $this->here)
			&& !preg_match('/\/reportes\/stock_pdf_activos_fijos_html\/[\d]+/', $this->here)
			&& !preg_match('/\/existencias\/comprobante_traslado_pdf\/[\d]+/', $this->here)
			&& !preg_match('/\/reportes\/productos_pdf_html\/([\w]+|[\d])/', $this->here)
			&& !preg_match('/\/activos_fijos\/comprobante_entrada_pdf\/[\d]+/', $this->here)
			&& !preg_match('/\/activos_fijos\/comprobante_traslado_pdf\/[\d]+/', $this->here)
			&& !preg_match('/\/bajas_activos_fijos\/comprobante_baja\/[\d]+/', $this->here)
			&& !preg_match('/\/exclusiones_activos_fijos\/comprobante_exclusion\/[\d]+/', $this->here)
			&& !preg_match('/\/detalles_activos_fijos_mantenciones\/comprobante_mantencion_pdf\/[\d]+/', $this->here)
			&& !preg_match('/\/reportes\/plancheta_activo_fijo_pdf_html\/[\d]+/', $this->here)
			&& !preg_match('/\/reportes\/plancheta_activo_fijo_agrupado_pdf_html\/[\d]+/', $this->here)
			&& !preg_match('/\/reportes\/existencias_pdf_html\/[\d]+\/([\w]+|[\d]+)/', $this->here)
			&& !preg_match('/\/reportes\/activos_fijos_pdf_html\/[\d]+\/([\w]+|[\d]+)/', $this->here)
			&& !preg_match('/\/reportes\/stock_centro_costo_pdf_html\/[\d]+/', $this->here)
			&& !preg_match('/\/reportes\/stock_centro_costo_general_pdf_html\/[\d]+/', $this->here)
			&& !preg_match('/\/reportes\/activos_fijos_general_pdf_html/', $this->here)
			&& !preg_match('/\/reportes\/traslados_por_fechas_activos_fijos_pdf_html\/[\d]+\/([\d]+|null)\/[\d]+\/([\w\-]+|[\d]+)\/([\w\-]+|[\d]+)/', $this->here)
			&& !preg_match('/\/reportes\/traslados_por_fechas_existencias_pdf_html\/[\d]+\/([\d]+|null)\/[\d]+\/([\w\-]+|[\d]+)\/([\w\-]+|[\d]+)/', $this->here)
			&& !preg_match('/\/reportes\/transito_pdf_html\/[\d]+/', $this->here)
			&& !preg_match('/\/solicitudes\/comprobante_html\/[\d]+/', $this->here)
			&& !preg_match('/\/reportes\/bienes_muebles_general_pdf_html\/[\d]+/', $this->here)) {
			
			$this->redirect(array('action' => 'index', 'controller' => 'login'));
			exit;
		}
	}
	
	public function afterFilter() {		
		// verificamos los permisos segun perfil de conexion
		if ($this->Session->check('userdata.permisos')) {
			$controller = $this->params['controller'];
			$action = $this->params['action'];
			$path = "/".$controller."/".$action;
			$permisos = $this->Session->read('userdata.permisos');
			
			// la pantalla de login y el main de usuarios se tiene que ver siempre
			if (!in_array($path, array("/login/index", "/login/logout", "/usuarios/main"))) {
				// sacamos metodos de generacion de docs y otros
				if (!preg_match("/(excel|genera|pdf|html|get|find|search|ajax|demo)/", $path)) {
					if (!in_array($path, $permisos)) {
						$this->Session->setFlash(__(utf8_encode('Su perfil de usuario no est� autorizado a hacer uso de este m�dulo.'), true));
						// aqui deberia guardar la accion en el log
						if (isset($_SERVER['HTTP_REFERER'])) {
							$this->redirect($_SERVER['HTTP_REFERER']);
						} else {
							$this->redirect(array('action' => 'index', 'controller' => 'login'));
						}
					}
				}
			}
		}
	}
	
	public function beforeRender() {
		if ($this->Session->check('userdata')) {
			$userdata = $this->Session->read('userdata');
			$this->set('usua_nombre', $userdata['Usuario']['usua_nombre']);
			
			if (isset($userdata['CentroCosto'])) {
				$this->set('ceco_nombre', $userdata['CentroCosto']['ceco_nombre']);
			}
		}
		
		//verificamos si existen variables del sitio en session
		if ($this->Session->check('sitedata')) {
			$site_logo = $this->Session->read('sitedata.site_logo');
			$site_title = $this->Session->read('sitedata.site_title');
			$this->set('site_logo', $site_logo);
			$this->set('site_title', $site_title);
			
		} else {
			$conf = $this->Configuracion->obtieneSiteConf();
			if (class_exists("Imagick")) {
				$logo = base64_decode($conf['site_logo']);
				$im_logo = new Imagick();
				$im_logo->readImageBlob($logo);
				// se transparenta si vienen con blanco
				$im_logo->paintTransparentImage($im_logo->getImagePixelColor(0, 0), 0, 0);
				$im_logo->thumbnailImage(80, 110, true);
				$this->Session->write('sitedata', array('site_logo' => base64_encode($im_logo), 'site_title' => $conf['site_title']));
				$this->set('site_logo', base64_encode($im_logo));
			}
			$this->set('site_title', $conf['site_title']);
		}
		
		// menu por perfil
		if ($this->Session->check('userdata.menu')) {
			$this->set('menu', $this->Session->read('userdata.menu'));
		}
		
		
	}
	
	public function ccArrayToHTML($centros_costos, $pinch = null) {
		$info = array();
		
		foreach ($centros_costos as $cc) {
			$info[$cc['CentroCosto']['ceco_id']] = $pinch." ".$cc['CentroCosto']['ceco_nombre'];
				
			if (isset($cc['children'])) {
				$children = $this->ccArrayToHTML($cc['children'], "---");
				
				foreach ($children as $key => $val) {
					$val = $pinch." ".$val;
					$val = str_replace("- -", "--", $val);
					$info[$key] = $val;
				}
			}
		}			
		return $info;
	}
	
	public function ccArrayToCcVector($centros_costos) {
		$info = array();
		
		foreach ($centros_costos as $cc) {
			$info[] = $cc['CentroCosto']['ceco_id'];
				
			if (isset($cc['children'])) {
				$children = $this->ccArrayToHTML($cc['children']);
				
				foreach ($children as $key => $val) {
					$info[] = $key;
				}
			}
		}			
		return $info;
	}
	
	public function sendMail($to, $subject, $template, $vars) {
		$info = $this->Configuracion->find('all');
		$conf = array();
			
		foreach ($info as $row) {
			$conf[$row['Configuracion']['conf_id']] = $row['Configuracion']['conf_valor'];
		}
		
		if (is_array($to)) {
			$this->Email->to = implode(", ", $to);
		} else {
			$this->Email->to = $to;
		}
		//$this->Email->replyTo 
		$this->Email->from = $conf['mail_from_name']." <".$conf['mail_from'].">";
		$this->Email->subject = $subject;
		$this->Email->sendAs = "html";
		$this->Email->template = $template;
		
		if ($conf['use_smtp'] == 0) {
			$this->Email->delivery = 'mail';
		} else {
			$this->Email->delivery = 'smtp';
			if ($conf['smtp_auth'] == 'false') {
				$conf['smtp_user'] = null;
				$conf['smtp_pass'] = null; 
			}
			
			$this->Email->smtpOptions = array(
				'port'     => $conf['smtp_port'],
				'timeout'  => $conf['smtp_timeout'],
				'host'     => $conf['smtp_host'],
				'username' => $conf['smtp_user'],
				'password' => $conf['smtp_pass']
			);
		}
		
		foreach ($vars as $key => $val) {
			$this->set($key, $val);
		}
		
		if ($this->Email->send()) {
			return true;
		} else {
			return false;
		}
	}
}
