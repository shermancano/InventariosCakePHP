<?php
class ExistenciasController extends AppController {
	var $name = 'Existencias';
	var $uses = array('Existencia', 'TrasladoExistencia', 'Producto');
	var $paginate = array(
		'Existencia' => array(
			'order' => array('Existencia.exis_fecha' => 'desc')
		)
	);
	
	function index_entrada() {
		$this->Existencia->recursive = 0;
		$centros_costos = $this->Session->read('userdata.CentroCosto.ceco_id');
		$entradas = $this->paginate('Existencia', array('AND' => array('Existencia.ceco_id' => $centros_costos , 'Existencia.tmov_id' => 1)));
		$this->set('entradas', $entradas);
	}
	
	function add_entrada() {
		if (!empty($this->data)) {
			$this->data['Existencia']['exis_fecha'] = date("Y-m-d H:i:s");
			// tmov_id = 1 (entrada)
			$tmov_id = 1;
			$ceco_id = $this->data['Existencia']['ceco_id']; 
			$this->data['Existencia']['exis_correlativo'] = $this->Existencia->obtieneCorrelativo($ceco_id, $tmov_id);
			$this->data['Existencia']['tmov_id'] = $tmov_id;
			// siempre el registro queda activo cuando es entrada
			$this->data['Existencia']['esre_id'] = 1;
			
			$this->Existencia->create();
			$this->Existencia->set($this->data);
			
			if (!isset($this->data['DetalleExistencia']) || sizeof($this->data['DetalleExistencia']) == 0) {
				$this->Session->setFlash(__('Debe por lo menos ingresar un detalle.', true));
			} else {
				if ($this->Existencia->validates()) {
					//limpiamos vars vacias
					$this->limpiaVars();
					$dataSource = $this->Existencia->getDataSource();
					$dataSource->begin($this->Existencia);
					
					if ($this->Existencia->save($this->data)) {
						$exis_id = $this->Existencia->id;
						// llenamos todo el detalle con el cod de existencia
						// unset serie en caso de ser vacio
						foreach ($this->data['DetalleExistencia'] as $key => $val) {
							$this->data['DetalleExistencia'][$key]['exis_id'] = $exis_id;
							
							if (trim($this->data['DetalleExistencia'][$key]['deex_serie']) == "") {
								$this->data['DetalleExistencia'][$key]['deex_serie'] = null;
							}
						}
						
						if ($this->Existencia->DetalleExistencia->saveAll($this->data['DetalleExistencia'])) {
							$dataSource->commit($this->Existencia);
							$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Nueva entrada de Existencia', $_REQUEST);
							$this->Session->setFlash(__('La entrada ha sido guardada de forma exitosa.', true));
							$this->redirect(array('action' => 'index_entrada'));
						} else {
							$dataSource->rollback($this->Existencia);
							$this->Session->setFlash(__(utf8_encode('No se pudo guardar la entrada, por favor inténtelo nuevamente'), true));
						}
					} else {
						$dataSource->rollback($this->Existencia);
						$this->Session->setFlash(__(utf8_encode('No se pudo guardar la entrada, por favor inténtelo nuevamente'), true));
					} 
				}
			}
		}
		
		$tipos_documentos = $this->Existencia->TipoDocumento->find('list', array('fields' => array('tido_id', 'tido_descripcion')));
		$this->set('tipos_documentos', $tipos_documentos);
		$proveedores = $this->Existencia->Proveedor->find('list', array('fields' => array('prov_id', 'prov_nombre'), 'order' => 'prov_nombre ASC'));
		$this->set('proveedores', $proveedores);
		$centros_costos = $this->Session->read('userdata.selectCC');
		$this->set('centros_costos', $centros_costos);
	}
	
	function edit_entrada($exis_id = null) {
		
		if (!$exis_id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index_entrada'));
		}
			
		if (!empty($this->data)) {
			// tmov_id = 1 (entrada)
			$this->data['Existencia']['tmov_id'] = 1;
			$this->Existencia->create();
			$this->Existencia->set($this->data);
				
			if (!isset($this->data['DetalleExistencia']) || sizeof($this->data['DetalleExistencia']) == 0) {
				$this->Session->setFlash(__('Debe por lo menos ingresar un detalle.', true));
			} else {	
				if ($this->Existencia->validates()) {	
					//limpiamos vars vacias
					$this->limpiaVars();
					$dataSource = $this->Existencia->getDataSource();
					$dataSource->begin($this->Existencia);
					
					if ($this->Existencia->save($this->data)) {
						$exis_id = $this->Existencia->id;
						
						// llenamos todo el detalle con el cod de existencia
						// unset serie en caso de ser vacio
						foreach ($this->data['DetalleExistencia'] as $key => $val) {
							if (!isset($this->data['DetalleExistencia'][$key]['exis_id'])) {
								$this->data['DetalleExistencia'][$key]['exis_id'] = $exis_id;
							}
							
							if (trim($this->data['DetalleExistencia'][$key]['deex_serie']) == "") {
								$this->data['DetalleExistencia'][$key]['deex_serie'] = null;
							}
						}
						
						if ($this->Existencia->DetalleExistencia->saveAll($this->data['DetalleExistencia'])) {
							$dataSource->commit($this->Existencia);
							$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación entrada de Existencia'), $_REQUEST);
							$this->Session->setFlash(__('La entrada ha sido guardada de forma exitosa.', true));
							$this->redirect(array('action' => 'index_entrada'));
						} else {
							$dataSource->rollback($this->Existencia);
							$this->Session->setFlash(__(utf8_encode('No se pudo guardar la entrada, por favor inténtelo nuevamente'), true));
						}
					} else {
						$dataSource->rollback($this->Existencia);
						$this->Session->setFlash(__(utf8_encode('No se pudo guardar la entrada, por favor inténtelo nuevamente'), true));
					}				
				}
			}
		}
		
		if (empty($this->data)) {
			$this->Existencia->recursive = 2;
			$this->data = $this->Existencia->read(null, $exis_id);
			$this->set('detalle_existencia_size', sizeof($this->data['DetalleExistencia']));
		}
		
		$proveedores = $this->Existencia->Proveedor->find('list', array('fields' => array('prov_id', 'prov_nombre'), 'order' => 'prov_nombre ASC'));
		$this->set('proveedores', $proveedores);
		$tipos_documentos = $this->Existencia->TipoDocumento->find('list', array('fields' => array('tido_id', 'tido_descripcion')));
		$this->set('tipos_documentos', $tipos_documentos);
		$centros_costos = $this->Session->read('userdata.selectCC');
		$this->set('centros_costos', $centros_costos);
	}
	
	function view_entrada($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Id invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Existencia->recursive = 2;
		$entradas = $this->Existencia->read(null, $id);
		$this->set('entrada', $entradas);
	}
	
	function delete_entrada($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index_entrada'));
		}
		if ($this->Existencia->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación entrada de Existencia'), $_REQUEST);
			$this->Session->setFlash(__('Entrada eliminada', true));
			$this->redirect(array('action'=>'index_entrada'));
		}
		$this->Session->setFlash(__('No se pudo eliminar la entrada', true));
		$this->redirect(array('action' => 'index_entrada'));
	}
	
	function comprobante_entrada($exis_id = null) {
		$this->layout = "ajax";
		if (!$exis_id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index_entrada'));
		}
		
		try {
			$pdf = new HTML_ToPDF("http://".$_SERVER['HTTP_HOST']."/existencias/comprobante_entrada_pdf/".$exis_id, "http://".$_SERVER['HTTP_HOST']);
			$pdf->setUnderlineLinks(true);
			$pdf->setScaleFactor('0.8');
			$pdf->setUseColor(true);
			$pdf->setFooter('center', '&nbsp;');
			$pdf->setHeader('center', '&nbsp;');
			$fp = fopen($pdf->convert(), "r");
			
			header("Content-type: application/pdf; name=Comprobante_Entrada_Existencias_".$exis_id.".pdf");
			header("Content-disposition: attachment; filename=Comprobante_Entrada_Existencias_".$exis_id.".pdf");
			
			if (rewind($fp)) {
				fpassthru($fp);
			}
			fclose($fp);
			
		} catch (HTML_ToPDFException $e) {
			echo $e->getMessage();
		}
	}
	
	function comprobante_entrada_pdf($exis_id) {
		$this->layout = "ajax";
		$this->Existencia->recursive = 0;
		$info = $this->Existencia->find('first', array('conditions' => array('Existencia.exis_id' => $exis_id)));
		$this->set("info" ,$info);
		$info_deex = $this->Existencia->DetalleExistencia->find('all', array('conditions' => array('DetalleExistencia.exis_id' => $exis_id), 'order' => 'Producto.prod_nombre asc'));
		$this->set("info_deex" ,$info_deex);
		
		$logo = $this->Configuracion->obtieneLogo();
		$fp = fopen($_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png", "w");
		fputs($fp, base64_decode($logo));
		fclose($fp);
	}
	
	function limpiaVars() {
		//limpiamos algunas vars
		if (isset($this->data['Existencia']['exis_orden_compra']) && trim($this->data['Existencia']['exis_orden_compra']) == "") {
			$this->data['Existencia']['exis_orden_compra'] = null;
		}
			
		if (isset($this->data['Existencia']['exis_nro_documento']) && trim($this->data['Existencia']['exis_nro_documento']) == "") {
			$this->data['Existencia']['exis_nro_documento'] = null;
		}
		
		if (isset($this->data['Existencia']['exis_comprobante_egreso']) && trim($this->data['Existencia']['exis_comprobante_egreso']) == "") {
			$this->data['Existencia']['exis_comprobante_egreso'] = null;
		}
			
		if (isset($this->data['Existencia']['exis_descripcion']) && trim($this->data['Existencia']['exis_descripcion']) == "") {
			$this->data['Existencia']['exis_descripcion'] = null;
		}
			
		if (isset($this->data['Existencia']['exis_observaciones']) && trim($this->data['Existencia']['exis_observaciones']) == "") {
			$this->data['Existencia']['exis_observaciones'] = null;
		}
	}
	
	function index_traslado() {
		$this->Existencia->recursive = 0;
		$centros_costos = $this->Session->read('userdata.CentroCosto.ceco_id');
		$traslados = $this->paginate('Existencia', array('AND' => array('Existencia.ceco_id' => $centros_costos , 'Existencia.tmov_id' => 2)));
		$this->set('traslados', $traslados);
	}
	
	function add_traslado() {
		if (!empty($this->data)) {
			$this->data['TrasladoExistencia']['exis_fecha'] = date("Y-m-d H:i:s");
			$this->TrasladoExistencia->set($this->data);
			
			// validates
			if ($this->TrasladoExistencia->validates()) {
				if (isset($this->data['DetalleExistencia']) && sizeof($this->data['DetalleExistencia']) > 0) {
			
					// limpiamos vars
					if (trim($this->data['TrasladoExistencia']['exis_descripcion']) == "") {
						$this->data['TrasladoExistencia']['exis_descripcion'] = null;
					}
					
					// limpiamos vars
					if (trim($this->data['TrasladoExistencia']['exis_observaciones']) == "") {
						$this->data['TrasladoExistencia']['exis_observaciones'] = null;
					}
					
					// transaccion
					$dataSource = $this->TrasladoExistencia->getDataSource();
					$dataSource->begin($this->TrasladoExistencia);
					
					// primero: salvamos traslado desde CC padre
					$tmov_id = 2; // <-- traslado
					$ceco_id = $this->data['TrasladoExistencia']['ceco_id'];
					$this->data['TrasladoExistencia']['exis_correlativo'] = $this->Existencia->obtieneCorrelativo($ceco_id, $tmov_id);
					$this->data['TrasladoExistencia']['tmov_id'] = $tmov_id;
					$this->data['TrasladoExistencia']['esre_id'] = 2;
					$this->TrasladoExistencia->create();
					
					if ($this->TrasladoExistencia->save($this->data)) {
						
						// segundo: salvamos detalle de traslado de CC padre
						if ($this->TrasladoExistencia->DetalleExistencia->saveTraslado($this->TrasladoExistencia->id, $this->data)) {
							
							// tercero: salvamos entrada para el CC hijo
							$tmov_id = 1; // <-- entrada
							$this->data['TrasladoExistencia']['ceco_id_padre'] = $this->data['TrasladoExistencia']['ceco_id'];
							$this->data['TrasladoExistencia']['ceco_id'] = $this->data['TrasladoExistencia']['ceco_id_hijo'];				
							$this->data['TrasladoExistencia']['exis_correlativo'] = $this->Existencia->obtieneCorrelativo($this->data['TrasladoExistencia']['ceco_id'], $tmov_id);
							$this->data['TrasladoExistencia']['tmov_id'] = $tmov_id;
							$this->data['TrasladoExistencia']['esre_id'] = 2;
							$this->data['TrasladoExistencia']['exis_id_padre'] = $this->TrasladoExistencia->id;
							unset($this->data['TrasladoExistencia']['ceco_id_hijo']);
							$this->TrasladoExistencia->create();
							
							if ($this->TrasladoExistencia->save($this->data)) {
								
								// cuarto: salvamos detalle de traslado de CC hijo
								$exis_id = $this->TrasladoExistencia->id;
								if ($this->TrasladoExistencia->DetalleExistencia->saveTraslado($exis_id, $this->data)) {
									$dataSource->commit($this->TrasladoExistencia);
									
									// enviamos correo de aviso (solo si el mail del destino no es vacío)
									list($vars, $responsables) = $this->Existencia->infoMailNuevoTraslado($exis_id);
									$vars['http_host'] = $_SERVER['HTTP_HOST'];
									$vars['mail_responsable'] = $responsables;
									
									if (is_array($responsables) && sizeof($responsables) > 0) {
										if ($this->sendMail($vars['mail_responsable'], utf8_encode("Nuevo traslado (existencia)"), "nuevo_traslado", $vars)) {
											$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Nuevo traslado de Existencia', $_REQUEST);
											$this->Session->setFlash(__('El traslado ha sido guardado de forma exitosa.', true));
											$this->redirect(array('action' => 'index_traslado'));
										} else {
											$this->Session->setFlash(__(utf8_encode('No se pudo enviar el correo electrónico de aviso de traslado.'), true));
											$this->redirect(array('action' => 'index_traslado'));
										}
									
									} else {
										$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Nuevo Traslado de Existencia'), $_REQUEST);
										$this->Session->setFlash(__('El traslado ha sido guardado de forma exitosa.', true));
										$this->redirect(array('action' => 'index_traslado'));
									}
								} else {
									$this->Session->setFlash(__(utf8_encode('No se pudo guardar el traslado, por favor inténtelo nuevamente'), true));
									$dataSource->rollback($this->TrasladoExistencia);
								}
							} else {
								$this->Session->setFlash(__(utf8_encode('No se pudo guardar el traslado, por favor inténtelo nuevamente'), true));
								$dataSource->rollback($this->TrasladoExistencia);
							}
						} else {
							$this->Session->setFlash(__(utf8_encode('No se pudo guardar el traslado, por favor inténtelo nuevamente'), true));
							$dataSource->rollback($this->TrasladoExistencia);
						}
					} else {
						$this->Session->setFlash(__(utf8_encode('No se pudo guardar el traslado, por favor inténtelo nuevamente'), true));
						$dataSource->rollback($this->TrasladoExistencia);
					}
				} else {
					$this->Session->setFlash(__('Debe por lo menos ingresar un detalle.', true));
				}
			}
		}
	
		$centros_costos = $this->Session->read('userdata.selectCCAll');
		$ceco_id = $this->Session->read('userdata.CentroCosto.ceco_id');
		$this->set('centros_costos', $centros_costos);
		$this->set('ceco_id', $ceco_id);
	}
	
	function edit_traslado($exis_id) {
	
		if (!$exis_id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index_traslado'));
		}
	
		if (!empty($this->data)) {
			$this->TrasladoExistencia->create();
			$this->TrasladoExistencia->set($this->data);
			
			if (!isset($this->data['DetalleExistencia']) || sizeof($this->data['DetalleExistencia']) == 0) {
				$this->Session->setFlash(__('Debe por lo menos ingresar un detalle.', true));
				$this->redirect($_SERVER['HTTP_REFERER']);
			} else {
				if ($this->TrasladoExistencia->validates()) {
					// transaccion
					$dataSource = $this->TrasladoExistencia->getDataSource();
					$dataSource->begin($this->TrasladoExistencia);
				
					if ($this->TrasladoExistencia->save($this->data)) {
						$exis_id = $this->TrasladoExistencia->id;
					
						if ($this->TrasladoExistencia->DetalleExistencia->updTraslado($exis_id, $this->data)) {
							$dataSource->commit($this->TrasladoExistencia);
							
							// enviamos correo de aviso (solo si el mail del destino no es vacío)
							list($vars, $responsables) = $this->Existencia->infoMailEditTraslado($exis_id);
							$vars['mail_responsable'] = $responsables;
							$vars['http_host'] = $_SERVER['HTTP_HOST'];
							
							if (is_array($vars['mail_responsable']) && sizeof($vars['mail_responsable']) > 0) {
								if ($this->sendMail($vars['mail_responsable'], utf8_encode("Edición de entrada desde ".$vars['ceco_nombre_padre']." (existencia)"), "edicion_traslado", $vars)) {
									$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación Traslado de Existencia'), $_REQUEST);
									$this->Session->setFlash(__('El traslado ha sido guardado de forma exitosa.', true));
									$this->redirect(array('action' => 'index_traslado'));
								} else {
									$this->Session->setFlash(__(utf8_encode('No se pudo enviar el correo electrónico de aviso de traslado.'), true));
									$this->redirect(array('action' => 'index_traslado'));
								}
									
							} else {
								$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación Traslado de Existencia'), $_REQUEST);
								$this->Session->setFlash(__('El traslado ha sido guardado de forma exitosa.', true));
								$this->redirect(array('action' => 'index_traslado'));
							}
							
						} else {
							$this->Session->setFlash(__(utf8_encode('No se pudo guardar el detalle del traslado, por favor inténtelo nuevamente'), true));
							$dataSource->rollback($this->TrasladoExistencia);
							$this->redirect($_SERVER['HTTP_REFERER']);
						}
					} else {
						$this->Session->setFlash(__(utf8_encode('No se pudo guardar el traslado, por favor inténtelo nuevamente'), true));
						$dataSource->rollback($this->TrasladoExistencia);
						$this->redirect($_SERVER['HTTP_REFERER']);
					}
				}
			}
		}
		
		$ceco_id = $this->Session->read('userdata.CentroCosto.ceco_id');
		
		if (empty($this->data)) {
			$this->TrasladoExistencia->recursive = 0;
			$this->data = $this->TrasladoExistencia->read(null, $exis_id);
			$fields = array('DetalleExistencia.*'
						   ,'Producto.*'
						   ,'(total_entradas_exis(ceco_id, "DetalleExistencia"."prod_id", deex_serie, deex_fecha_vencimiento, deex_precio)
						    - total_traslados_exis(ceco_id, "DetalleExistencia"."prod_id", deex_serie, deex_fecha_vencimiento, deex_precio)) as "DetalleExistencia__total"');
			
			$deex_info = $this->TrasladoExistencia->DetalleExistencia->find('all', array('fields' => $fields, 'conditions' => array('DetalleExistencia.exis_id' => $exis_id)));
			$size = sizeof($deex_info);
			$this->set('size_detalle_existencia', $size);
			$this->set('deex_info', $deex_info);
		}
		
		$centros_costos = $this->Session->read('userdata.selectCC');
		$this->set('centros_costos', $centros_costos);
		$this->set('ceco_id', $ceco_id);
	}
	
	function view_traslado($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Id invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Existencia->recursive = 2;
		$entradas = $this->Existencia->read(null, $id);
		$this->set('entrada', $entradas);
	}
	
	function delete_traslado($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index_traslado'));
		}
		if ($this->Existencia->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación Traslado de Existencia'), $_REQUEST);
			$this->Session->setFlash(__('Traslado eliminado', true));
			$this->redirect(array('action'=>'index_traslado'));
		}
		$this->Session->setFlash(__('No se pudo eliminar el traslado', true));
		$this->redirect(array('action' => 'index_traslado'));
	}
	
	function acepta_recepcion($exis_id) {
		$this->layout = 'ajax';
		
		if ($this->Existencia->aceptaRecepcion($exis_id)) {
			// manda mail solo si existe mail de recepcion
			list($vars, $responsables) = $this->Existencia->infoMailAceptaRecepcion($exis_id);
			$vars['http_host'] = $_SERVER['HTTP_HOST'];
			$vars['mail_responsable'] = $responsables;
			
			if (is_array($responsables) && sizeof($responsables) > 0) {		
				if ($this->sendMail($vars['mail_responsable'], utf8_encode("Recepción exitosa (existencia)"), "acepta_recepcion", $vars)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Recepción Traslado de Existencia'), $_REQUEST);
					$this->Session->setFlash(__(utf8_encode('La recepción ha sido exitosa'), true));
					$this->redirect(array('action' => 'index_entrada'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo enviar el correo electrónico de recepción.'), true));
					$this->redirect(array('action' => 'index_entrada'));
				}
			} else {
				$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Recepción Traslado de Existencia'), $_REQUEST);
				$this->Session->setFlash(__(utf8_encode('La recepción ha sido exitosa'), true));
				$this->redirect(array('action' => 'index_entrada'));
			}
		} else {
			$this->Session->setFlash(__(utf8_encode('La recepción ha fallado. Por favor, inténtelo nuevamente'), true));
			$this->redirect(array('action' => 'index_entrada'));
		}
	}
	
	function rechaza_recepcion($exis_id) {
		$this->layout = 'ajax';
		
		if ($this->Existencia->rechazaRecepcion($exis_id)) {
			// manda mail solo si existe mail de recepcion
			list($vars, $responsables) = $this->Existencia->infoMailRechazaRecepcion($exis_id);
			$vars['http_host'] = $_SERVER['HTTP_HOST'];
			$vars['mail_responsable'] = $responsables;
			// Registro Rechazo
			$usua_id = $this->Session->read('userdata.Usuario.usua_id');
			$reex_fecha = date('Y-m-d H:i:s');
			$vars['motivo'] = $_REQUEST['motivo'];			
			
			// datos para registrar el rechazo
			$data = array(
				'RechazoExistencia' => array(
					'exis_id' => $exis_id,
					'usua_id' => $usua_id,
					'reex_fecha' => $reex_fecha,
					'reex_motivo' => $_REQUEST['motivo'])
			); 
			
			$this->Existencia->RechazoExistencia->create();
			if ($this->Existencia->RechazoExistencia->save($data)) {
				if ($this->sendMail($vars['mail_responsable'], utf8_encode("Rechazo de traslado (existencia)"), "rechaza_recepcion", $vars)) {
					$res = "ok";
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Rechazo Traslado de Existencia'), $_REQUEST);
					$this->Session->setFlash(__(utf8_encode('Se ha rechazado la recepción.'), true));
				} else {
					$res = "email";
					$this->Session->setFlash(__(utf8_encode('No se pudo enviar el correo electrónico de rechazo.'), true));
				}
			} else {
				$res = "rechazo";
				$this->Session->setFlash(__(utf8_encode('No se pudo guardar el detalle de rechazo.'), true));
			}
		}
		$this->set("res", $res);
	}
	
	function comprobante_traslado($exis_id = null) {
		$this->layout = "ajax";
		if (!$exis_id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index_traslado'));
		}
		
		try {
			$pdf = new HTML_ToPDF("http://".$_SERVER['HTTP_HOST']."/existencias/comprobante_traslado_pdf/".$exis_id, "http://".$_SERVER['HTTP_HOST']);
			$pdf->setUnderlineLinks(true);
			$pdf->setScaleFactor('0.8');
			$pdf->setUseColor(true);
			$pdf->setFooter('center', '&nbsp;');
			$pdf->setHeader('center', '&nbsp;');
			$fp = fopen($pdf->convert(), "r");
			
			header("Content-type: application/pdf; name=Comprobante_Traslado_Existencias_".$exis_id.".pdf");
			header("Content-disposition: attachment; filename=Comprobante_Traslado_Existencias_".$exis_id.".pdf");
			
			if (rewind($fp)) {
				fpassthru($fp);
			}
			fclose($fp);
			
		} catch (HTML_ToPDFException $e) {
			echo $e->getMessage();
		}
	}
	
	function comprobante_traslado_pdf($exis_id) {
		$this->layout = "ajax";
		$this->Existencia->recursive = 0;
		$info = $this->Existencia->find('first', array('conditions' => array('Existencia.exis_id' => $exis_id)));
		$info_deex  = $this->Existencia->DetalleExistencia->find('all', array('conditions' => array('DetalleExistencia.exis_id' => $exis_id), 'order' => 'Producto.prod_nombre asc'));
		$this->set("info" ,$info);
		$this->set("info_deex" ,$info_deex);
		
		$logo = $this->Configuracion->obtieneLogo();
		$fp = fopen($_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png", "w");
		fputs($fp, base64_decode($logo));
		fclose($fp);
	}
	
	function searchAll($tmov_id = 1) {
		$this->layout = 'ajax';
		$string = stripslashes($_GET['term']);
		
		//buscamos existencias
		$ceco_id = $this->Session->read('userdata.CentroCosto.ceco_id');
		$info = $this->Existencia->searchAll($string, $ceco_id, $tmov_id);
		$existencias = array();
		
		if (sizeof($info) > 0) {
			foreach ($info as $exis) {
				$exis = array_pop($exis);
				$value = sprintf("%012d", $exis['exis_correlativo'])." - ".date("d-m-Y H:i:s", strtotime($exis['exis_fecha']));
				$existencias[] = array('value' => $value, 'label' => $value, 'exis_id' => $exis['exis_id']);
			}
		}
		$this->set('json_info', json_encode($existencias));
	}
}
?>
