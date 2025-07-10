<?php
class SolicitudesController extends AppController {
	var $uses = array('Solicitud');
	var $paginate = array('Solicitud' => array('order' => array('Solicitud.soli_fecha' => 'desc')));
	
	function index () {
		$this->Solicitud->recursive = 0;
		$ceco_id = $this->Session->read('userdata.CentroCosto.ceco_id');
		$solicitudes = $this->paginate('Solicitud', array('AND' => array('Solicitud.ceco_id' => $ceco_id)));
		$this->set('solicitudes', $solicitudes);
	}
	
	function add() {
		if (!empty($this->data)) {
			$this->data['Solicitud']['esso_id'] = 2;
			$this->data['Solicitud']['soli_fecha'] = date("Y-m-d H:i:s");
			$this->data['Solicitud']['soli_correlativo'] = $this->Solicitud->obtieneCorrelativo($this->data['Solicitud']['ceco_id']);
			$this->Solicitud->create();
			$this->Solicitud->set($this->data);
			$dataSource = $this->Solicitud->getDataSource();
			$dataSource->begin($this->Solicitud);
			
			if (!isset($this->data['DetalleSolicitud']) || sizeof($this->data['DetalleSolicitud']) == 0) {
				$this->Session->setFlash(__(utf8_encode('Debe por lo menos ingresar un detalle'), true));
			} else {
			
				if ($this->Solicitud->validates()) {
					if ($this->Solicitud->save($this->data)) {
						$soli_id = $this->Solicitud->id;
						
						if ($this->Solicitud->DetalleSolicitud->save($this->data, $soli_id)) {
							$dataSource->commit($this->Solicitud);
							$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Nueva Solicitud', $_REQUEST);
							
							// enviamos correo de aviso (solo si el mail del destino no es vacío)
							// correo a cc interno o proveedor según corresponda
							if (isset($this->data['Solicitud']['ceco_id_hacia'])) {
								$ceco_id_hacia = $this->data['Solicitud']['ceco_id_hacia'];
								list($vars, $responsables) = $this->Solicitud->infoMailNuevaSolicitudCC($ceco_id_hacia, $soli_id);
								$vars['http_host'] = $_SERVER['HTTP_HOST'];
								$vars['mail_responsable'] = $responsables;
								$template = 'nueva_solicitud_cc';
								
							} else if (isset($this->data['Solicitud']['prov_id'])) {
								$template = 'nueva_solicitud_prov';
								$prov_id = $this->data['Solicitud']['prov_id'];
								$vars = $this->Solicitud->infoMailNuevaSolicitudProv($prov_id, $soli_id);
								
								if ($vars['prov_email'] == null) {
									$responsables = array();
								} else {
									$responsables = array($vars['prov_email']);
								}
								
								$vars['mail_responsable'] = $responsables;
							}
							
							if (is_array($responsables) && sizeof($responsables) > 0) {
								// generamos pdf
								$logo = $this->Configuracion->obtieneLogo();
								$fp = @fopen($_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png", "w");
		 
								if($fp==true){
									fputs($fp, base64_decode($logo));
									fclose($fp);
			
									try {
										$pdf = new HTML_ToPDF("http://".$_SERVER['HTTP_HOST']."/solicitudes/comprobante_html/".$soli_id, "http://".$_SERVER['HTTP_HOST']);
										$pdf->setUnderlineLinks(true);
										$pdf->setScaleFactor('0.8');
										$pdf->setUseColor(true);
										$pdf->setFooter('center', utf8_decode('Página $N'));
										$pdf->setHeader('center', '&nbsp;');
										//$pdf->setUseCss(true);
										//$pdf->setAdditionalCSS('* {margin:0; padding:0;}');
										$file = $pdf->convert();
										$nFile = "solicitud_".sprintf("%012d", $this->data['Solicitud']['soli_correlativo']).".pdf";
										$attachments = array($nFile => $file);
										
									} catch (HTML_ToPDFException $e) {
										$this->Session->setFlash(__('No se puedo generar el PDF', true));
										$this->redirect(array('action' => 'add'));
									}
								} else {
									$this->Session->setFlash(__('No se puede generar el logo para el reporte', true));
									$this->redirect(array('action' => 'add'));
								}
							
								$this->sendMail($vars['mail_responsable'], utf8_encode("Nueva solicitud"), $template, $vars, $attachments);
							}			
							
							$this->Session->setFlash(__('La solicitud ha sido guardada', true));
							$this->redirect(array('action' => 'index'));
							
						} else {
							$dataSource->rollback($this->Solicitud);
							$this->Session->setFlash(__(utf8_encode('No se pudo guardar la solicitud. Por favor, inténtelo nuevamente'), true));
						}
					} else {
						$dataSource->rollback($this->Solicitud);
						$this->Session->setFlash(__(utf8_encode('No se pudo guardar la solicitud. Por favor, inténtelo nuevamente'), true));
					}
				}
			}
		}
		
		$tipo_solicitud = $this->Solicitud->TipoSolicitud->find('list', array('fields' => 'tiso_nombre'));
		$this->set('tipo_solicitud', $tipo_solicitud);		
		$centros_costos = $this->Session->read('userdata.selectCCAll');
		$this->set('centros_costos', $centros_costos);
		$proveedores = $this->Solicitud->Proveedor->find('list', array('fields' => 'prov_nombre', 'order' => array('Proveedor.prov_nombre' => 'asc')));
		$this->set('proveedores', $proveedores);
		$ceco_id = $this->Session->read('userdata.CentroCosto.ceco_id');
		$this->set('ceco_id', $ceco_id);
	}
	
	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Id invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Solicitud->recursive = 0;
		$solicitud = $this->Solicitud->read(null, $id);
		$this->Solicitud->DetalleSolicitud->recursive = 2;
		$deso_info = $this->Solicitud->DetalleSolicitud->find('all', array('order' => array('Producto.prod_nombre'), 'conditions' => array('Solicitud.soli_id' => $id)));
		$this->set('solicitud', $solicitud);
		$this->set('deso_info', $deso_info);
	}
	
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Solicitud->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode("Eliminación Solicitud"), $_REQUEST);
			$this->Session->setFlash(__('La solicitud ha sido eliminada', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('No se pudo eliminar el modelo', true));
		$this->redirect(array('action' => 'index'));
	}
	
	function comprobante($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		
		$logo = $this->Configuracion->obtieneLogo();
		$fp = @fopen($_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png", "w");
		 
		if($fp==true){
			fputs($fp, base64_decode($logo));
			fclose($fp);
			
			try {
				$pdf = new HTML_ToPDF("http://".$_SERVER['HTTP_HOST']."/solicitudes/comprobante_html/".$id, "http://".$_SERVER['HTTP_HOST']);
				$pdf->setUnderlineLinks(true);
				$pdf->setScaleFactor('0.8');
				$pdf->setUseColor(true);
				$pdf->setFooter('center', utf8_decode('Página $N'));
				$pdf->setHeader('center', '&nbsp;');
				//$pdf->setUseCss(true);
				//$pdf->setAdditionalCSS('* {margin:0; padding:0;}');
				
				$fp = fopen($pdf->convert(), "r");
				
				ob_clean();
				header("Content-type: application/pdf; name=Comprobante_solicitud_".$id.".pdf");
				header("Content-disposition: attachment; filename=Comprobante_solicitud_".$id.".pdf");
				
				if (rewind($fp)) {
					fpassthru($fp);
				}
				fclose($fp);
				
			} catch (HTML_ToPDFException $e) {
				echo $e->getMessage();
			}
		}else{
			$this->Session->setFlash(__('No se puede generar el logo para el reporte', true));
			$this->redirect(array('action' => 'stock'));
		}
	}
	
	function comprobante_html($id) {
		$this->layout = 'ajax';
		$info = $this->Solicitud->find('first', array('conditions' => array('Solicitud.soli_id' => $id)));
		$this->Solicitud->DetalleSolicitud->recursive = 2;
		$deso_info = $this->Solicitud->DetalleSolicitud->find('all', array('order' => array('Producto.prod_id' => 'asc'), 'conditions' => array('DetalleSolicitud.soli_id' => $id)));
		$this->set('info', $info);
		$this->set('deso_info', $deso_info);
	}
	
	function pendientes_internas() {
		$this->Solicitud->recursive = 0;
		$ceco_id = $this->Session->read('userdata.CentroCosto.ceco_id');
		$this->paginate = array(
			'Solicitud' => array(
				'order' => array('Solicitud.soli_fecha' => 'desc'),
				'conditions' => array('Solicitud.ceco_id_hacia' => $ceco_id)
			)
		);
		$solicitudes = $this->paginate();
		$this->set('solicitudes', $solicitudes);
	}
	
	function pendientes_externas() {
		$this->Solicitud->recursive = 0;
		$ceco_id = $this->Session->read('userdata.CentroCosto.ceco_id');
		
		$this->paginate = array(
			'Solicitud' => array(
				'order' => array('Solicitud.soli_fecha' => 'desc'),
				'conditions' => array('Solicitud.ceco_id' => $ceco_id, 'Solicitud.prov_id is not null')
			)
		);
		
		$solicitudes = $this->paginate();
		$this->set('solicitudes', $solicitudes);
	}
	
	function acepta_solicitud($soli_id) {
		$this->layout = 'ajax';
		
		if ($this->Solicitud->aceptaSolicitud($soli_id)) {
			
			list($vars, $responsables) = $this->Solicitud->infoMailAceptaSolicitud($soli_id);
			// manda mail solo si existe mail de recepcion
			$vars['http_host'] = $_SERVER['HTTP_HOST'];
			$vars['mail_responsable'] = $responsables;
			
			if (isset($vars['mail_responsable']) && sizeof($vars['mail_responsable']) > 0) {
				$this->sendMail($vars['mail_responsable'], utf8_encode("Solicitud aceptada"), "acepta_solicitud", $vars);
			} 
			
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), ('Recepción de Solicitud'), $_REQUEST);
			$this->Session->setFlash(__(('La recepción ha sido exitosa'), true));
			
			if ($vars['prov_nombre'] != "") {
				$this->redirect(array('action' => 'pendientes_externas'));
			} else {
				$this->redirect(array('action' => 'pendientes_internas'));
			}
		} else {
			$this->Session->setFlash(__(('La recepción ha fallado. Por favor, inténtelo nuevamente'), true));
			$this->redirect(array('action' => 'pendientes'));
		}
	}
	
	function rechaza_solicitud($soli_id) {
		$this->layout = 'ajax';
		
		if ($this->Solicitud->rechazaSolicitud($soli_id)) {
		
			list($vars, $responsables) = $this->Solicitud->infoMailRechazaSolicitud($soli_id);
			// manda mail solo si existe mail de recepcion
			$vars['http_host'] = $_SERVER['HTTP_HOST'];
			$vars['mail_responsable'] = $responsables;
			$vars['motivo'] = $_REQUEST['motivo'];
			
			// Registro Rechazo
			$usua_id = $this->Session->read('userdata.Usuario.usua_id');
			$reso_fecha = date('Y-m-d H:i:s');	
			
			// datos para registrar el rechazo
			$data = array(
				'RechazoSolicitud' => array(
					'soli_id' => $soli_id,
					'usua_id' => $usua_id,
					'reso_fecha' => $reso_fecha,
					'reso_motivo' => $_REQUEST['motivo'])
			);
			
			$this->Solicitud->RechazoSolicitud->create();
			if ($this->Solicitud->RechazoSolicitud->save($data)) {
			
				if (isset($vars['mail_responsable']) && sizeof($vars['mail_responsable']) > 0) {
					$this->sendMail($vars['mail_responsable'], utf8_encode("Rechazo de solicitud"), "rechaza_solicitud", $vars);
				} 
				
				$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Rechazo Solicitud', $_REQUEST);
				$this->Session->setFlash(__('Se ha rechazado la recepción.', true));
				$res = "ok";
				
			} else {
				$res = "rechazo";
				$this->Session->setFlash(__(utf8_encode('No se pudo guardar el detalle de rechazo.'), true));
			}
		}
		
		if ($vars['prov_nombre'] != "") {
			$from = "ext";
		} else {
			$from = "int";
		}
		
		$this->set("res", json_encode(array("res" => $res, "from" => $from)));
	}
	
}
?>