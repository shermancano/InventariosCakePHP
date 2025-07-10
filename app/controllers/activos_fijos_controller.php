<?php

set_time_limit(0);

class ActivosFijosController extends AppController {
	var $name = 'ActivosFijos';
	var $uses = array('ActivoFijo', 'UbicacionActivoFijo', 'TrasladoActivoFijo', 'Configuracion', 'Usuario', 'TrazabilidadActivoFijo', 'ActivoFijoDocumento');
	var $paginate = array(
		'ActivoFijo' => array(
			'order' => array('ActivoFijo.acfi_fecha' => 'desc')
		),
		'DetalleActivoFijo' => array(
			'limit' => 10,
			'order' => array('Producto.prod_nombre' => 'asc') 
		)
	);
	
	function index_entrada() {
		$this->ActivoFijo->recursive = 0;
		$centros_costos = $this->Session->read('userdata.CentroCosto.ceco_id');
		$activos_fijos = $this->paginate('ActivoFijo', array('AND' => array('ActivoFijo.ceco_id' => $centros_costos , 'ActivoFijo.tmov_id' => 1)));
		$this->set('activos_fijos', $activos_fijos);
		$this->set('ceco_id', $this->Session->read('userdata.CentroCosto.ceco_id'));
	}
	
	function index_traslado() {
		$this->ActivoFijo->recursive = 0;
		$centros_costos = $this->Session->read('userdata.CentroCosto.ceco_id');
		$activos_fijos = $this->paginate('ActivoFijo', array('AND' => array('ActivoFijo.ceco_id' => $centros_costos , 'ActivoFijo.tmov_id' => 2)));
		$this->set('activos_fijos', $activos_fijos);
		$this->set('ceco_id', $this->Session->read('userdata.CentroCosto.ceco_id'));
	}
	
	function add_entrada() {
	
		if (!empty($this->data)) {
			$this->data['ActivoFijo']['acfi_fecha'] = date("Y-m-d H:i:s");
			// tmov_id = 1 (entrada)
			$tmov_id = 1;
			$ceco_id = $this->data['ActivoFijo']['ceco_id']; 
			$this->data['ActivoFijo']['acfi_correlativo'] = $this->ActivoFijo->obtieneCorrelativo($ceco_id, $tmov_id);
			$this->data['ActivoFijo']['tmov_id'] = $tmov_id;
			// siempre el registro queda activo cuando es entrada
			$this->data['ActivoFijo']['esre_id'] = 1;
		
			$this->ActivoFijo->create();
			$this->ActivoFijo->set($this->data);
			
			if ($this->ActivoFijo->validates()) {			
				if (!isset($this->data['DetalleActivoFijo']) || sizeof($this->data['DetalleActivoFijo']) == 0) {
					$this->Session->setFlash(__('Debe por lo menos ingresar un detalle.', true));
				} else {
					//limpiamos vars vacias
					$this->limpiaVars();
					
					$dataSource = $this->ActivoFijo->getDataSource();
					$dataSource->begin($this->ActivoFijo);
					
					if ($this->ActivoFijo->save($this->data)) {
						$acfi_id = $this->ActivoFijo->id;
						// Almacenamos imagen adjunta
						if ($this->data['ActivoFijoDocumento']['acfd_contenido']['error'] == 0) {
							$tmp = $this->data['ActivoFijoDocumento']['acfd_contenido']['tmp_name'];
							$fp = fopen($tmp, "r");
							$bin = fread($fp, filesize($tmp));
							$bin = base64_encode($bin);
							fclose($fp);
							$activoFijoDocumento = array(
								'ActivoFijoDocumento' => array(
									'acfi_id' => $acfi_id,
									'acfd_nombre' => $this->data['ActivoFijoDocumento']['acfd_contenido']['name'],
									'acfd_archivo' => $bin,
									'acfd_type' => $this->data['ActivoFijoDocumento']['acfd_contenido']['type'],
									'acfd_size' => $this->data['ActivoFijoDocumento']['acfd_contenido']['size']
								)
							);				
							$this->ActivoFijoDocumento->save($activoFijoDocumento);
						} else {
							unset($this->data['ActivoFijoDocumento']);
						}
						
						// llenamos todo el detalle con el ultimo cod de activo fijo
						// generamos cods de barra
						// guardamos segun cantidad
						foreach ($this->data['DetalleActivoFijo'] as $deaf) {
						
							$deaf['acfi_id'] = $acfi_id;
							$prod_id = $deaf['prod_id'];
							$cob_barra_base = $this->ActivoFijo->generaCodBarraBase($prod_id);
							
							if (!isset($prod_info['max_correlativo'][$prod_id])) {
								$prod_info['max_correlativo'][$prod_id] = $this->ActivoFijo->obtieneMaxCorrelativoCodBarra($prod_id);
							}
							
							for ($i=1; $i<=$deaf['deaf_cantidad']; $i++) {
								$this->ActivoFijo->DetalleActivoFijo->create();
								
								$prod_info['max_correlativo'][$prod_id]++;
								$prod_info['max_correlativo'][$prod_id] = substr($prod_info['max_correlativo'][$prod_id], -7);
								$deaf['deaf_codigo'] = $cob_barra_base.sprintf("%07d", $prod_info['max_correlativo'][$prod_id]);
								$data['DetalleActivoFijo'] = $deaf;
								
								if (!$this->ActivoFijo->DetalleActivoFijo->save($data)) {
									$dataSource->rollback($this->ActivoFijo);
									$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Nueva Entrada de Activo Fijo'), $_REQUEST);
									$this->Session->setFlash(__(utf8_encode('No se pudo guardar parte del detalle de la entrada, por favor inténtelo nuevamente'), true));
									$this->redirect(array('action' => 'index_entrada'));
								}
							}
						}
						
						$dataSource->commit($this->ActivoFijo);	
						$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Nueva Entrada de Activo Fijo'), $_REQUEST);
						$this->Session->setFlash(__('La entrada ha sido guardada de forma exitosa.', true));
						$this->redirect(array('action' => 'index_entrada'));
					} else {
						$this->Session->setFlash(__(utf8_encode('No se pudo guardar la entrada, por favor inténtelo nuevamente'), true));
						$dataSource->rollback($this->ActivoFijo);
					}
				}				
			}
		}
		
		$tipos_documentos = $this->ActivoFijo->TipoDocumento->find('list', array('fields' => array('tido_id', 'tido_descripcion')));
		$this->set('tipos_documentos', $tipos_documentos);
		$proveedores = $this->ActivoFijo->Proveedor->find('list', array('fields' => array('prov_id', 'prov_nombre'), 'order' => 'prov_nombre ASC'));
		$this->set('proveedores', $proveedores);
		$financiamientos = $this->ActivoFijo->Financiamiento->find('list', array('fields' => array('fina_id', 'fina_nombre'), 'order' => 'fina_nombre ASC'));
		$this->set('financiamientos', $financiamientos);
		$centros_costos = $this->Session->read('userdata.selectCC');
		$this->set('centros_costos', $centros_costos);
		
		$marcas = $this->ActivoFijo->DetalleActivoFijo->Marca->find('list', array('fields' => array('marc_id', 'marc_nombre')));
		$this->set('marcas', $marcas);
		$propiedades = $this->ActivoFijo->DetalleActivoFijo->Propiedad->find('list', array('fields' => array('prop_id', 'prop_nombre')));
		$this->set('propiedades', $propiedades);
		$colores = $this->ActivoFijo->DetalleActivoFijo->Color->find('list', array('fields' => array('colo_id', 'colo_nombre')));
		$this->set('colores', $colores);
		$situaciones = $this->ActivoFijo->DetalleActivoFijo->Situacion->find('list', array('fields' => array('situ_id', 'situ_nombre')));
		$this->set('situaciones', $situaciones);
		$modelos = $this->ActivoFijo->DetalleActivoFijo->Modelo->find('list', array('fields' => array('mode_id', 'mode_nombre')));
		$this->set('modelos', $modelos);
		$tipos_resoluciones = $this->ActivoFijo->TipoResolucion->find('list', array('fields' => 'tire_nombre'));
		$this->set('tipos_resoluciones', $tipos_resoluciones);
	}
	
	function add_traslado () {
		if (!empty($this->data)) {			
			$this->TrasladoActivoFijo->set($this->data);
			
			if ($this->TrasladoActivoFijo->validates()) {
				if (!isset($this->data['DetalleActivoFijo']) || sizeof($this->data['DetalleActivoFijo']) == 0) {
					$this->Session->setFlash(__('Debe por lo menos ingresar un detalle.', true));	
				} else {
					$dataSource = $this->TrasladoActivoFijo->getDataSource();
					$dataSource->begin($this->TrasladoActivoFijo);
					
					$this->data['TrasladoActivoFijo']['tmov_id'] = 2;
					$this->data['TrasladoActivoFijo']['esre_id'] = 2;
					$this->data['TrasladoActivoFijo']['acfi_fecha'] = date("Y-m-d H:i:s");
					$this->data['TrasladoActivoFijo']['acfi_correlativo'] = $this->ActivoFijo->obtieneCorrelativo($this->data['TrasladoActivoFijo']['ceco_id'], $this->data['TrasladoActivoFijo']['tmov_id']);
					$this->TrasladoActivoFijo->create();
					
					if ($this->TrasladoActivoFijo->save($this->data)) {
						$acfi_id = $this->TrasladoActivoFijo->id;
						if ($this->TrasladoActivoFijo->DetalleActivoFijo->addTraslado($this->data, $acfi_id)) {
							$this->data['TrasladoActivoFijo']['tmov_id'] = 1;
							$this->data['TrasladoActivoFijo']['esre_id'] = 2;
							$this->data['TrasladoActivoFijo']['ceco_id_padre'] = $this->data['TrasladoActivoFijo']['ceco_id'];
							$this->data['TrasladoActivoFijo']['ceco_id'] = $this->data['TrasladoActivoFijo']['ceco_id_hijo'];
							$this->data['TrasladoActivoFijo']['acfi_id_padre'] = $this->TrasladoActivoFijo->id;
							$this->data['TrasladoActivoFijo']['acfi_correlativo'] = $this->ActivoFijo->obtieneCorrelativo($this->data['TrasladoActivoFijo']['ceco_id'], $this->data['TrasladoActivoFijo']['tmov_id']);
							unset($this->data['TrasladoActivoFijo']['ceco_id_hijo']);
							$this->TrasladoActivoFijo->create();
							
							if ($this->TrasladoActivoFijo->save($this->data)) {
								$acfi_id = $this->TrasladoActivoFijo->id;
								if ($this->TrasladoActivoFijo->DetalleActivoFijo->addTraslado($this->data, $acfi_id)) {
									$dataSource->commit($this->TrasladoActivoFijo);
									
									// enviamos correo de aviso (solo si el mail del destino no es vacío)
									list($vars, $responsables) = $this->ActivoFijo->infoMailNuevoTraslado($acfi_id);
									$vars['http_host'] = $_SERVER['HTTP_HOST'];
									$vars['mail_responsable'] = $responsables;									
									
									if (is_array($responsables) && sizeof($responsables) > 0) {
										if ($this->sendMail($vars['mail_responsable'], utf8_encode("Nuevo traslado (activo fijo)"), "nuevo_traslado_acfi", $vars)) {
											$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Nuevo Traslado de Activo Fijo'), $_REQUEST);
											$this->Session->setFlash(__('El traslado ha sido guardado de forma exitosa.', true));
											$this->redirect(array('action' => 'index_traslado'));											
										} else {
											$this->Session->setFlash(__(utf8_encode('No se pudo enviar el correo electrónico de aviso de traslado.'), true));
											$this->redirect(array('action' => 'index_traslado'));
										}
									} else {
										$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Nuevo Traslado de Activo Fijo'), $_REQUEST);
										$this->Session->setFlash(__('El traslado ha sido guardado de forma exitosa.', true));
										$this->redirect(array('action' => 'index_traslado'));
									}
									
								} else {
									$dataSource->rollback($this->TrasladoActivoFijo);
									$this->Session->setFlash(__(utf8_encode('No se pudo guardar el traslado, por favor inténtelo nuevamente'), true));
									$this->redirect(array('action' => 'index_traslado'));
								}
							} else {
								$dataSource->rollback($this->TrasladoActivoFijo);
								$this->Session->setFlash(__(utf8_encode('No se pudo guardar el traslado, por favor inténtelo nuevamente'), true));
								$this->redirect(array('action' => 'index_traslado'));
							}
						} else {
							$dataSource->rollback($this->TrasladoActivoFijo);
							$this->Session->setFlash(__(utf8_encode('No se pudo guardar el traslado, por favor inténtelo nuevamente'), true));
							$this->redirect(array('action' => 'index_traslado'));
						}
					} else {
						$dataSource->rollback($this->TrasladoActivoFijo);
						$this->Session->setFlash(__(utf8_encode('No se pudo guardar el traslado, por favor inténtelo nuevamente'), true));
						$this->redirect(array('action' => 'index_traslado'));
					}
				}
			}
		}
	
		$centros_costos = $this->Session->read('userdata.selectCCAll');
		$ceco_id = $this->Session->read('userdata.CentroCosto.ceco_id');
		$this->set('centros_costos', $centros_costos);
		$this->set('ceco_id', $ceco_id);
		$findCentroCostoPadre = $this->ActivoFijo->CentroCosto->find('first',
			array(
				'fields' => array(
					'CentroCosto.ceco_id',
					'CentroCosto.ceco_nombre'
				),
				'order' => array(
					'CentroCosto.ceco_id' => 'ASC'
				)
			)
		);
		
		$centroCostoPadre = $findCentroCostoPadre['CentroCosto']['ceco_id'];
		$findCentroCostos = $this->ActivoFijo->CentroCosto->findCentroCostoPadrePaint($centroCostoPadre);
		$centros_costos_paint = array();
		foreach ($findCentroCostos as $row) {
			$centros_costos_paint[] = $row[0]['ceco_id']; 
		}
		$this->set('centros_costos_paint', $centros_costos_paint);
	}
	
	function edit_traslado ($acfi_id = null) {
		$this->Session->setFlash(__(utf8_encode('Módulo en construcción'), true));
		$this->redirect(array('action' => 'index_traslado'));
	}
	
	function edit_entrada($acfi_id = null) {
	
		if (!$acfi_id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
			
		if (!empty($this->data)) {
			$this->ActivoFijo->create();
			$this->ActivoFijo->set($this->data);
			$ceco_id = $this->data['ActivoFijo']['ceco_id']; 
			
			if ($this->ActivoFijo->validates()) {
				if (!isset($this->data['DetalleActivoFijo']) || sizeof($this->data['DetalleActivoFijo']) == 0) {
					$this->Session->setFlash(__('Debe por lo menos ingresar un detalle.', true));
				} else {
					//limpiamos vars vacias
					$this->limpiaVars();
					
					$dataSource = $this->ActivoFijo->getDataSource();
					$dataSource->begin($this->ActivoFijo);
					
					if ($this->ActivoFijo->save($this->data)) {
						$acfi_id = $this->ActivoFijo->id;
						if ($this->data['ActivoFijoDocumento']['acfd_contenido']['error'] == 0) {						
							$tmp = $this->data['ActivoFijoDocumento']['acfd_contenido']['tmp_name'];
							$fp = fopen($tmp, "r");
							$bin = fread($fp, filesize($tmp));
							$bin = base64_encode($bin);
							fclose($fp);
							
							$this->data['ActivoFijoDocumento']['acfi_id'] = $acfi_id;
							$this->data['ActivoFijoDocumento']['acfd_nombre'] = $this->data['ActivoFijoDocumento']['acfd_contenido']['name'];
							$this->data['ActivoFijoDocumento']['acfd_archivo'] = $bin;
							$this->data['ActivoFijoDocumento']['acfd_type'] = $this->data['ActivoFijoDocumento']['acfd_contenido']['type'];
							$this->data['ActivoFijoDocumento']['acfd_size'] = $this->data['ActivoFijoDocumento']['acfd_contenido']['size'];									
							$this->ActivoFijoDocumento->save($this->data['ActivoFijoDocumento']);
						} else {
							unset($this->data['ActivoFijoDocumento']);
						}
						
						// llenamos todo el detalle con el ultimo cod de activo fijo
						// generamos cods de barra
						// guardamos segun cantidad
						foreach ($this->data['DetalleActivoFijo'] as $deaf) {
							if (!isset($deaf['prod_id'])) continue;
						
							$deaf['acfi_id'] = $acfi_id;
							$prod_id = $deaf['prod_id'];
							$cob_barra_base = $this->ActivoFijo->generaCodBarraBase($prod_id);
							
							if (!isset($prod_info['max_correlativo'][$prod_id])) {
								$prod_info['max_correlativo'][$prod_id] = $this->ActivoFijo->obtieneMaxCorrelativoCodBarra($prod_id);
							}
							
							$prod_info['max_correlativo'][$prod_id]++;
							$prod_info['max_correlativo'][$prod_id] = substr($prod_info['max_correlativo'][$prod_id], -7);
							$deaf['deaf_codigo'] = $cob_barra_base.sprintf("%07d", $prod_info['max_correlativo'][$prod_id]);
							$this->ActivoFijo->DetalleActivoFijo->create();
							
							if (!$this->ActivoFijo->DetalleActivoFijo->save(array('DetalleActivoFijo' => $deaf))) {
								$dataSource->rollback($this->ActivoFijo);
								$this->Session->setFlash(__(utf8_encode('No se pudo guardar parte del detalle de la entrada, por favor inténtelo nuevamente'), true));
								$this->redirect(array('action' => 'index_entrada'));
							}
						}
						
						$dataSource->commit($this->ActivoFijo);
						$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación Entrada de Activo Fijo'), $_REQUEST);
						$this->Session->setFlash(__('La entrada ha sido guardada de forma exitosa.', true));
						$this->redirect(array('action' => 'index_entrada'));
						
					} else {
						$this->Session->setFlash(__(utf8_encode('No se pudo guardar la entrada, por favor inténtelo nuevamente'), true));
						$dataSource->rollback($this->ActivoFijo);
					}
				}
			}
		}
		
		if (empty($this->data)) {
			$this->ActivoFijo->recursive = 2;
			$this->data = $this->ActivoFijo->read(null, $acfi_id);
			$this->set("deaf_size", sizeof($this->data['DetalleActivoFijo']));
		}
	
		$tipos_documentos = $this->ActivoFijo->TipoDocumento->find('list', array('fields' => array('tido_id', 'tido_descripcion')));
		$this->set('tipos_documentos', $tipos_documentos);
		$proveedores = $this->ActivoFijo->Proveedor->find('list', array('fields' => array('prov_id', 'prov_nombre'), 'order' => 'prov_nombre ASC'));
		$this->set('proveedores', $proveedores);
		$financiamientos = $this->ActivoFijo->Financiamiento->find('list', array('fields' => array('fina_id', 'fina_nombre'), 'order' => 'fina_nombre ASC'));
		$this->set('financiamientos', $financiamientos);
		$centros_costos = $this->Session->read('userdata.selectCC');
		$this->set('centros_costos', $centros_costos);
		
		$marcas = $this->ActivoFijo->DetalleActivoFijo->Marca->find('list', array('fields' => array('marc_id', 'marc_nombre')));
		$this->set('marcas', $marcas);
		$propiedades = $this->ActivoFijo->DetalleActivoFijo->Propiedad->find('list', array('fields' => array('prop_id', 'prop_nombre')));
		$this->set('propiedades', $propiedades);
		$colores = $this->ActivoFijo->DetalleActivoFijo->Color->find('list', array('fields' => array('colo_id', 'colo_nombre')));
		$this->set('colores', $colores);
		$situaciones = $this->ActivoFijo->DetalleActivoFijo->Situacion->find('list', array('fields' => array('situ_id', 'situ_nombre')));
		$this->set('situaciones', $situaciones);
		$modelos = $this->ActivoFijo->DetalleActivoFijo->Modelo->find('list', array('fields' => array('mode_id', 'mode_nombre')));
		$this->set('modelos', $modelos);
		$tipos_resoluciones = $this->ActivoFijo->TipoResolucion->find('list', array('fields' => 'tire_nombre'));
		$this->set('tipos_resoluciones', $tipos_resoluciones);		
		$param_iva = $this->Configuracion->find('first', array('conditions' => array('Configuracion.conf_id' => 'param_iva')));
		
		if (sizeof($param_iva) > 0 && is_array($param_iva)) {
			$valor_iva = $param_iva['Configuracion']['conf_valor'];
		} else {
			$valor_iva = 0;
		}
		
		$this->set('valor_iva', $valor_iva);
	}
	
	function view_entrada($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Id invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		
		$this->ActivoFijo->recursive = 0;
		$entradas = $this->ActivoFijo->read(null, $id);
		$ceco_id = $entradas['ActivoFijo']['ceco_id'];
		$this->set('entrada', $entradas);
		
		$detalles = $this->paginate('DetalleActivoFijo', array('AND' => array('DetalleActivoFijo.acfi_id' => $id)));
		$this->set('detalles', $detalles);
		
		$total = $this->ActivoFijo->DetalleActivoFijo->find('all', array('fields' => array('sum(deaf_precio) as total'), 'conditions' => array('DetalleActivoFijo.acfi_id' => $id)));
		$this->set('total', $total[0][0]['total']);
		
		$param_iva = $this->Configuracion->find('first', array('conditions' => array('Configuracion.conf_id' => 'param_iva')));
		
		if (sizeof($param_iva) > 0 && is_array($param_iva)) {
			$valor_iva = $param_iva['Configuracion']['conf_valor'];
		} else {
			$valor_iva = 0;
		}
		
		$ubicacion = $this->UbicacionActivoFijo->CentroCosto->findUbicacion($ceco_id);				
		$ubicacion = "/".str_replace(" / ", " / ", $ubicacion);		
		//$ubicacion = basename(dirname($ubicacion))."/".basename($ubicacion);		
	
		if (strpos($ubicacion, "/") == 0) $ubicacion = substr($ubicacion, 1);
		$ubicacion = utf8_decode($ubicacion);
		
		$ubicacionPadre = '';
		if (!empty($entradas['ActivoFijo']['ceco_id_padre'])) {
			$ceco_id_padre = $entradas['ActivoFijo']['ceco_id_padre'];
			$ubicacionPadre = $this->UbicacionActivoFijo->CentroCosto->findUbicacion($ceco_id_padre);				
			$ubicacionPadre = "/".str_replace(" / ", " / ", $ubicacionPadre);			
		
			if (strpos($ubicacionPadre, "/") == 0) $ubicacionPadre = substr($ubicacionPadre, 1);
			$ubicacionPadre = utf8_decode($ubicacionPadre);
		}
		
		$this->set('valor_iva', $valor_iva);
		$this->set('ubicacion', $ubicacion);
		$this->set('ubicacionPadre', $ubicacionPadre);
	}
	
	function view_traslado($id) {
		if (!$id) {
			$this->Session->setFlash(__('Id invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->ActivoFijo->recursive = 2;
		$entradas = $this->ActivoFijo->read(null, $id);
		$ceco_id = $entradas['ActivoFijo']['ceco_id'];
		$ceco_id_hijo = $entradas['ActivoFijo']['ceco_id_hijo'];
		$this->set('entrada', $entradas);
		
		$detalles = $this->paginate('DetalleActivoFijo', array('AND' => array('DetalleActivoFijo.acfi_id' => $id)));
		$this->set('detalles', $detalles);
		
		$total = $this->ActivoFijo->DetalleActivoFijo->find('all', array('fields' => array('sum(deaf_precio) as total'), 'conditions' => array('DetalleActivoFijo.acfi_id' => $id)));
		$this->set('total', $total[0][0]['total']);
		
		$param_iva = $this->Configuracion->find('first', array('conditions' => array('Configuracion.conf_id' => 'param_iva')));
		
		if (sizeof($param_iva) > 0 && is_array($param_iva)) {
			$valor_iva = $param_iva['Configuracion']['conf_valor'];
		} else {
			$valor_iva = 0;
		}
		
		// Ubicacion Centro de Costo Desde
		$ubicacion = $this->UbicacionActivoFijo->CentroCosto->findUbicacion($ceco_id);				
		$ubicacion = "/".str_replace(" / ", " / ", $ubicacion);		
		//$ubicacion = basename(dirname($ubicacion))."/".basename($ubicacion);			
		if (strpos($ubicacion, "/") == 0) $ubicacion = substr($ubicacion, 1);
		$ubicacion = utf8_decode($ubicacion);
		
		// Ubicacion Centro de Costo Hacia
		$ubicacionPadre = $this->UbicacionActivoFijo->CentroCosto->findUbicacion($ceco_id_hijo);				
		$ubicacionPadre = "/".str_replace(" / ", " / ", $ubicacionPadre);		
		//$ubicacion = basename(dirname($ubicacion))."/".basename($ubicacion);	
		if (strpos($ubicacionPadre, "/") == 0) $ubicacionPadre = substr($ubicacionPadre, 1);
		$ubicacionPadre = utf8_decode($ubicacionPadre);
		
		$this->set('valor_iva', $valor_iva);
		$this->set('ubicacion', $ubicacion);
		$this->set('ubicacionPadre', $ubicacionPadre);
	}
	
	function delete_entrada($id) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index_entrada'));
		}
		
		// esta funcion comentada elimina detalles y ubicaciones
		//if ($this->ActivoFijo->deleteAll($id)) {
		
		if ($this->ActivoFijo->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación Entrada de Activo Fijo'), $_REQUEST);
			$this->Session->setFlash(__('Entrada eliminada', true));
			$this->redirect(array('action'=>'index_entrada'));
		}
		$this->Session->setFlash(__('No se pudo eliminar la entrada', true));
		$this->redirect(array('action' => 'index_entrada'));	
	}	
	
	function delete_traslado($id) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index_traslado'));
		}
		if ($this->ActivoFijo->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación Traslado de Activo Fijo'), $_REQUEST);
			$this->Session->setFlash(__('Traslado Eliminado', true));
			$this->redirect(array('action'=>'index_traslado'));
		}
		$this->Session->setFlash(__('No se pudo eliminar el Traslado', true));
		$this->redirect(array('action' => 'index_traslado'));		
	}
	
	function limpiaVars() {
		//limpiamos algunas vars
		if (isset($this->data['ActivoFijo']['acfi_orden_compra']) && trim($this->data['ActivoFijo']['acfi_orden_compra']) == "") {
			$this->data['ActivoFijo']['acfi_orden_compra'] = null;
		}
			
		if (isset($this->data['ActivoFijo']['acfi_nro_documento']) && trim($this->data['ActivoFijo']['acfi_nro_documento']) == "") {
			$this->data['ActivoFijo']['acfi_nro_documento'] = null;
		}
			
		if (isset($this->data['ActivoFijo']['acfi_descripcion']) && trim($this->data['ActivoFijo']['acfi_descripcion']) == "") {
			$this->data['ActivoFijo']['acfi_descripcion'] = null;
		}
			
		if (isset($this->data['ActivoFijo']['acfi_observaciones']) && trim($this->data['ActivoFijo']['acfi_observaciones']) == "") {
			$this->data['ActivoFijo']['acfi_observaciones'] = null;
		}
	}
	
	function comprobante_entrada($acfi_id = null){		
		$this->layout = "ajax";
		if (!$acfi_id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index_entrada'));
		}
		
		try {
			$pdf = new HTML_ToPDF("http://".$_SERVER['HTTP_HOST']."/activos_fijos/comprobante_entrada_pdf/".$acfi_id, "http://".$_SERVER['HTTP_HOST']);
			$pdf->setUnderlineLinks(true);
			$pdf->setScaleFactor('0.8');
			$pdf->setUseColor(true);
			$pdf->setFooter('center', 'Página $N');
			$pdf->setHeader('center', '&nbsp;');
			$fp = fopen($pdf->convert(), "r");
			
			ob_clean();
			header("Content-type: application/pdf; name=Comprobante_Alta_de_Inventario_".$acfi_id.".pdf");
			header("Content-disposition: attachment; filename=Comprobante_Alta_de_Inventario_".$acfi_id.".pdf");
			
			if (rewind($fp)) {
				fpassthru($fp);
			}
			fclose($fp);
			
		} catch (HTML_ToPDFException $e) {
			echo $e->getMessage();
		}
	}
	
	function comprobante_entrada_pdf($acfi_id) {
		$this->layout = "ajax";
		$this->ActivoFijo->recursive = 2;
		$info = $this->ActivoFijo->find('first', array('conditions' => array('ActivoFijo.acfi_id' => $acfi_id)));
		$info_acfi = $this->ActivoFijo->DetalleActivoFijo->find('all', array('conditions' => array('ActivoFijo.acfi_id' => $acfi_id), 'order' => 'Producto.prod_nombre asc'));
		$param_iva = $this->Configuracion->find('first', array('conditions' => array('Configuracion.conf_id' => 'param_iva')));
		$ceco_id = $info['ActivoFijo']['ceco_id'];
		
		if (sizeof($param_iva) > 0 && is_array($param_iva)) {
			$valor_iva = $param_iva['Configuracion']['conf_valor'];
		} else {
			$valor_iva = 0;
		}
		
		$ubicacion = $this->UbicacionActivoFijo->CentroCosto->findUbicacion($ceco_id);				
		$ubicacion = "/".str_replace(" / ", " / ", $ubicacion);		
		//$ubicacion = basename(dirname($ubicacion))."/".basename($ubicacion);		
	
		if (strpos($ubicacion, "/") == 0) $ubicacion = substr($ubicacion, 1);
		$ubicacion = utf8_decode($ubicacion);

		$ubicacionPadre = '';
		if (!empty($info['ActivoFijo']['ceco_id_padre'])) {
			$ceco_id_padre = $info['ActivoFijo']['ceco_id_padre'];
			$ubicacionPadre = $this->UbicacionActivoFijo->CentroCosto->findUbicacion($ceco_id_padre);				
			$ubicacionPadre = "/".str_replace(" / ", " / ", $ubicacionPadre);			
		 
			if (strpos($ubicacionPadre, "/") == 0) $ubicacionPadre = substr($ubicacionPadre, 1);
			$ubicacionPadre = utf8_decode($ubicacionPadre);
		}

		$this->set('ubicacion', $ubicacion);		
		$this->set("info" ,$info);
		$this->set("info_acfi", $info_acfi);
		$this->set('valor_iva', $valor_iva);
		$this->set('ubicacionPadre', $ubicacionPadre);
		
		$logo = $this->Configuracion->obtieneLogo();
		$fp = fopen($_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png", "w");
		fputs($fp, base64_decode($logo));
		fclose($fp);
	}
	
	function comprobante_traslado($acfi_id) {
		$this->layout = "ajax";
		if (!$acfi_id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index_traslado'));
		}
		
		try {
			$pdf = new HTML_ToPDF("http://".$_SERVER['HTTP_HOST']."/activos_fijos/comprobante_traslado_pdf/".$acfi_id, "http://".$_SERVER['HTTP_HOST']);
			$pdf->setUnderlineLinks(true);
			$pdf->setScaleFactor('0.8');
			$pdf->setUseColor(true);
			$pdf->setFooter('center', 'Página $N');
			$pdf->setHeader('center', '&nbsp;');
			$fp = fopen($pdf->convert(), "r");
			
			header("Content-type: application/pdf; name=Comprobante_Traslado_Activos_Fijos_".$acfi_id.".pdf");
			header("Content-disposition: attachment; filename=Comprobante_Traslado_Activos_Fijos_".$acfi_id.".pdf");
			
			if (rewind($fp)) {
				fpassthru($fp);
			}
			fclose($fp);
			
		} catch (HTML_ToPDFException $e) {
			echo $e->getMessage();
		}
	}
	
	function comprobante_traslado_pdf($acfi_id) {
		$this->layout = "ajax";
		$this->ActivoFijo->recursive = 2;
		$info = $this->ActivoFijo->find('first', array('conditions' => array('ActivoFijo.acfi_id' => $acfi_id)));
		$ceco_id = $info['ActivoFijo']['ceco_id'];
		$ceco_id_hijo = $info['ActivoFijo']['ceco_id_hijo'];
		$info_acfi = $this->ActivoFijo->DetalleActivoFijo->find('all', array('conditions' => array('ActivoFijo.acfi_id' => $acfi_id), 'order' => 'Producto.prod_nombre asc'));
		$param_iva = $this->Configuracion->find('first', array('conditions' => array('Configuracion.conf_id' => 'param_iva')));
		
		if (sizeof($param_iva) > 0 && is_array($param_iva)) {
			$valor_iva = $param_iva['Configuracion']['conf_valor'];
		} else {
			$valor_iva = 0;
		}
		
		// Ubicacion Centro de Costo Desde
		$ubicacion = $this->UbicacionActivoFijo->CentroCosto->findUbicacion($ceco_id);				
		$ubicacion = "/".str_replace(" / ", " / ", $ubicacion);		
		//$ubicacion = basename(dirname($ubicacion))."/".basename($ubicacion);			
		if (strpos($ubicacion, "/") == 0) $ubicacion = substr($ubicacion, 1);
		$ubicacion = utf8_decode($ubicacion);
		
		// Ubicacion Centro de Costo Hacia
		$ubicacionPadre = $this->UbicacionActivoFijo->CentroCosto->findUbicacion($ceco_id_hijo);				
		$ubicacionPadre = "/".str_replace(" / ", " / ", $ubicacionPadre);		
		//$ubicacion = basename(dirname($ubicacion))."/".basename($ubicacion);	
		if (strpos($ubicacionPadre, "/") == 0) $ubicacionPadre = substr($ubicacionPadre, 1);
		$ubicacionPadre = utf8_decode($ubicacionPadre);
		
		$this->set("info" ,$info);
		$this->set("info_acfi", $info_acfi);
		$this->set("valor_iva", $valor_iva);
		$this->set("ubicacion", $ubicacion);
		$this->set("ubicacionPadre", $ubicacionPadre);
		
		$logo = $this->Configuracion->obtieneLogo();
		$fp = fopen($_SERVER['DOCUMENT_ROOT']."/app/webroot/files/logo.png", "w");
		fputs($fp, base64_decode($logo));
		fclose($fp);
	}
	
	function codigos_barra() {	
		$ceco_id = $this->Session->read('userdata.CentroCosto.ceco_id');
		$this->UbicacionActivoFijo->recursive = 0;
		$conds = array('AND' => array('UbicacionActivoFijo.ceco_id' => $ceco_id));
		$detalles = $this->paginate('UbicacionActivoFijo', $conds);
		$this->set('detalles', $detalles);		
	}
	
	function genera_codigo_barra($deaf_codigo) {
		$this->layout = "ajax";
		
		if (!class_exists("Imagick")) {
			$this->Session->setFlash(__(utf8_encode('No existe la extensión Imagick. Consulte al administrador del sistema.'), true));
			$this->redirect(array('action' => 'codigos_barra'));
		}
		
		// buscamos vars de configuracion de cod de barra
		$conf = $this->Configuracion->obtieneCodBarraConf();
		
		// sacamos info del producto
		$info_prod = $this->UbicacionActivoFijo->find('first', array('conditions' => array('ubaf_codigo' => $deaf_codigo)));
		
		$prod_nombre = utf8_decode($info_prod['Producto']['prod_nombre']);
		$ceco_nombre = utf8_decode($info_prod['CentroCosto']['ceco_nombre']);
		$ubaf_fecha = date("d-m-Y H:i:s", strtotime($info_prod['UbicacionActivoFijo']['ubaf_fecha']));
		$ubaf_serie = utf8_decode($info_prod['UbicacionActivoFijo']['ubaf_serie']);
		$propiedad = utf8_decode($info_prod['Propiedad']['prop_nombre']);
		
		$ubicacion = $this->UbicacionActivoFijo->CentroCosto->findUbicacion($info_prod['UbicacionActivoFijo']['ceco_id']);				
		$ubicacion = "/".str_replace(" / ", "/", $ubicacion);		
		//$ubicacion = basename(dirname($ubicacion))."/".basename($ubicacion);		
	
		if (strpos($ubicacion, "/") == 0) $ubicacion = substr($ubicacion, 1);
		$ubicacion = utf8_decode($ubicacion);
	
		// Mostramos ubicación de fin a inicio. (Reversa)
		$ubicacionInicioFin = array_reverse(explode('/', $ubicacion));
		$ubicacionReversa = implode('/', $ubicacionInicioFin);
		
		// Cambiamos ancho en caso de tener titulo
		if (isset($conf['barcode_titulo_nombre']) && $conf['barcode_titulo_nombre'] != '0') {
			$im = imagecreatetruecolor($conf['barcode_width'], 145);
		} else {
			$im = imagecreatetruecolor($conf['barcode_width'], 125);		
		}
	
		$white = imagecolorallocate($im, 255, 255, 255);
		$black = imagecolorallocate($im, 0, 0, 0);
		
		$largoNombre = 4;
		$largoUbicacion = 107;
		$largoCodbarra = 20;
		$largoLogo = 20;
		$anchoLogo = 50;
		$widthBarcode = 100;
		$widthCodigo = 123;
		$heightCodigo = 90;	
		
		// Cambiamos ancho en caso de tener titulo
		if (isset($conf['barcode_titulo_nombre']) && $conf['barcode_titulo_nombre'] != '0') {					
			imagefilledrectangle($im, 0, 0, 400, 145, $white);
			// Cambiamos largo de texto al agregar titulo
			$largoNombre = 22;
			$largoUbicacion = 130;
			$largoCodbarra = 42;
			$largoLogo = 39;
		} else {			
			imagefilledrectangle($im, 0, 0, 400, 125, $white);			
		}
		
		if ($conf['barcode_logo'] == 1) {
			try {
				$logo = base64_decode($this->Configuracion->obtieneLogo());
				$im_logo = new Imagick();
				$im_logo->readImageBlob($logo);
				// Antes
				//$im_logo->thumbnailImage(80, 80, true);
				$im_logo->thumbnailImage(70, 70, true);
				
			} catch (ImagickException $e) {
				echo $e->getMessage();
			}
			 
			$widthBarcode = 105;
			$logo = imagecreatefromstring($im_logo);			
			imagecopy($im, $logo, 15, $largoLogo, 0, 0, imagesx($logo), imagesy($logo));
			
		}
		
		try {
			$barcode = new Image_Barcode2();
			
			if ($conf['barcode_type'] == "code128") {
				$barcode_type = Image_Barcode2::BARCODE_CODE128;
			} elseif ($conf['barcode_type'] == "code39") {
				$barcode_type = Image_Barcode2::BARCODE_CODE39;
			} elseif ($conf['barcode_type'] == "int25") {
				// Cambiamos parametros de codigo
				if (isset($conf['barcode_titulo_nombre']) && $conf['barcode_titulo_nombre'] != '0') {							
					$heightCodigo = 105;
				}
				
				if ($conf['barcode_logo'] == 1) {					
					$widthCodigo = 160;
				} else {
					$widthBarcode = 85;
				}
				
				$largoCodbarra = 30;
				$anchoLogo = 70;
				$barcode_type = Image_Barcode2::BARCODE_INT25;						
				
				// Se agrega codigo de barra ya que este tipo de codificación no lo muestra
				imagestring ($im, 2, $widthCodigo, $heightCodigo, $deaf_codigo, $black);
			} elseif ($conf['barcode_type'] == "postnet") {
				$barcode_type = Image_Barcode2::BARCODE_POSTNET;
			} elseif ($conf['barcode_type'] == "upca") {
				$barcode_type = Image_Barcode2::BARCODE_UPCA;
			}
			
			$cod_barra = $barcode->draw($deaf_codigo, $barcode_type, Image_Barcode2::IMAGE_PNG, false);
		} catch (Image_Barcode2_Exception $e) {
			echo $e->getMessage();
		}
		
		// Mostramos titulo
		if (isset($conf['barcode_titulo_nombre']) && $conf['barcode_titulo_nombre'] != '0') {
			// Quitamos titulo en caso de que el bien sea personal
			if ($propiedad != 'PERSONAL') {
				$text = $conf['barcode_titulo_nombre'];			
			} else {				
				$text = $propiedad;	
			}
			
			$largo = strlen($text);		
			$ancho_texto = ($largo * 8.8) / 2;
			$center = 181;
			$xpos = $center - $ancho_texto;
			$largoCodbarra = 45;			
	
			// Titulo
			imagestring ($im, 5, $xpos, 4, $text, $black);
		}
		
		//imagecopyresized($im, $cod_barra, 55, 30, 0, 0, 347, 70, imagesx($cod_barra), imagesy($cod_barra));		
		//imagecopy ($im, $cod_barra, 100, 20, 0, 0, imagesx($cod_barra), imagesy($cod_barra));
		if ($conf['barcode_logo'] == 0) {
			imagecopy ($im, $cod_barra, $anchoLogo, $largoCodbarra, 0, 0, imagesx($cod_barra), imagesy($cod_barra));
		} else {
			imagecopy ($im, $cod_barra, $widthBarcode, $largoCodbarra, 0, 0, imagesx($cod_barra), imagesy($cod_barra));
		}
		
		if ($conf['barcode_prod'] == 1) {			
			imagestring ($im, 3, 10, $largoNombre, $prod_nombre, $black);
		}
		
		if ($conf['barcode_date'] == 1 && $conf['barcode_cc'] == 1) {
			//$str = $ubaf_fecha." - ".$ceco_nombre;
			// solicitado por rcarranza 13-08-2012
			$str = $ubaf_fecha." - ".$ubicacionReversa;
		} elseif ($conf['barcode_date'] == 1 && $conf['barcode_cc'] == 0) {
			$str = $ubaf_fecha;
		} elseif ($conf['barcode_date'] == 0 && $conf['barcode_cc'] == 1) {
			//$str = $ceco_nombre;
			// solicitado por rcarranza 13-08-2012
			$str = '';
			// Se debe cambiar el ancho de la imagen a 125 en configuraciones
			
			//imagestring ($im, 5, 10, 107, $ubicacion, $black);	
			imagestring ($im, 3, 10, $largoUbicacion, $ubicacionReversa, $black);	
		} elseif ($conf['barcode_date'] == 0 && $conf['barcode_cc'] == 0) {
			$str = null;
		}
		
		imagestring ($im, 2, 10, imagesy($im)-15, $str, $black);
		
		if (isset($conf['barcode_serie']) && $conf['barcode_serie'] == 1) {
			//imagestring ($im, 2, 10, 4, $ubaf_serie, $black);
		}
		
		ob_clean();
		header ("Content-Type: image/png");
		imagepng($im);		
	}	
	
	function genera_codigo_barra_demo($barcode_type = 'code128', $barcode_logo = 1, $barcode_prod = 1, $barcode_date = 1, $barcode_cc = 1, $barcode_height = 120, $barcode_width = 365, $barcode_serie = 1) {
		$this->layout = "ajax";
		
		if (!class_exists("Imagick")) {
			$this->Session->setFlash(__(utf8_encode('No existe la extensión Imagick. Consulte al administrador del sistema.'), true));
			$this->redirect(array('controller' => 'configuraciones', 'action' => 'index'));
		}
		
		$prod_nombre = "PRODUCTO PRUEBA";
		$ceco_nombre = "CENTRO COSTO PRUEBA/HIJO";
		$acfi_fecha = date("d-m-Y H:i:s");
		$deaf_codigo = "000000000000000000";
		$ubaf_serie = 'bbkwa12032012';
		
		$im = imagecreatetruecolor($barcode_width, $barcode_height);
		$white = imagecolorallocate($im, 255, 255, 255);
		$black = imagecolorallocate($im, 0, 0, 0);
		imagefilledrectangle($im, 0, 0, 400, 120, $white);
		
		if ($barcode_logo == 1) {
			try {
				$logo = base64_decode($this->Configuracion->obtieneLogo());
				$im_logo = new Imagick();
				$im_logo->readImageBlob($logo);
				$im_logo->thumbnailImage(80, 80, true);
				
			} catch (ImagickException $e) {
				echo $e->getMessage();
			}
			
			$logo = imagecreatefromstring($im_logo);
			imagecopy($im, $logo, 15, 12, 0, 0, imagesx($logo), imagesy($logo));
		}
		
		try {
			$barcode = new Image_Barcode2();
			
			if ($barcode_type == "code128") {
				$barcode_type = Image_Barcode2::BARCODE_CODE128;
			} elseif ($barcode_type == "code39") {
				$barcode_type = Image_Barcode2::BARCODE_CODE39;
			} elseif ($barcode_type == "int25") {
				$barcode_type = Image_Barcode2::BARCODE_INT25;
			} elseif ($barcode_type == "postnet") {
				$barcode_type = Image_Barcode2::BARCODE_POSTNET;
			} elseif ($barcode_type == "upca") {
				$barcode_type = Image_Barcode2::BARCODE_UPCA;
			}
			
			$cod_barra = $barcode->draw($deaf_codigo, $barcode_type, Image_Barcode2::IMAGE_PNG, false);
		} catch (Image_Barcode2_Exception $e) {
			echo $e->getMessage();
		}
		
		//imagecopyresized($im, $cod_barra, 55, 30, 0, 0, 347, 70, imagesx($cod_barra), imagesy($cod_barra));
		imagecopy ($im, $cod_barra, 100, 20, 0, 0, imagesx($cod_barra), imagesy($cod_barra));
		
		if ($barcode_prod == 1) {
			imagestring ($im, 5, 110, 4, $prod_nombre, $black);
		}
		
		if ($barcode_date == 1 && $barcode_cc == 1) {
			$str = $acfi_fecha." - ".$ceco_nombre;
		} elseif ($barcode_date == 1 && $barcode_cc == 0) {
			$str = $acfi_fecha;
		} elseif ($barcode_date == 0 && $barcode_cc == 1) {
			$str = $ceco_nombre;
		} elseif ($barcode_date == 0 && $barcode_cc == 0) {
			$str = null;
		}
		
		imagestring ($im, 2, 10, imagesy($im)-15, $str, $black);
		
		if ($barcode_serie == 1) {
			imagestring ($im, 2, 10, 4, $ubaf_serie, $black);
		}
		
		header ("Content-Type: image/png");
		imagepng($im);
	}	
	
	function rechaza_recepcion($acfi_id) {
		$this->layout = 'ajax';
		
		if ($this->ActivoFijo->rechazaRecepcion($acfi_id)) {
			// manda mail solo si existe mail de recepcion
			list($vars, $responsables) = $this->ActivoFijo->infoMailRechazaRecepcion($acfi_id);
			$vars['http_host'] = $_SERVER['HTTP_HOST'];
			$vars['mail_responsable'] = $responsables;
			// Registro Rechazo
			$usua_id = $this->Session->read('userdata.Usuario.usua_id');
			$reaf_fecha = date('Y-m-d H:i:s');
			$vars['motivo'] = $_REQUEST['motivo'];			
			
			// datos para registrar el rechazo
			$data = array(
				'RechazoActivoFijo' => array(
					'acfi_id' => $acfi_id,
					'usua_id' => $usua_id,
					'reaf_fecha' => $reaf_fecha,
					'reaf_motivo' => $_REQUEST['motivo'])
			); 
			
			$this->ActivoFijo->RechazoActivoFijo->create();
			if ($this->ActivoFijo->RechazoActivoFijo->save($data)) {
				if ($this->sendMail($vars['mail_responsable'], utf8_encode("Rechazo de traslado (activo fijo)"), "rechaza_recepcion_acfi", $vars)) {
					$res = "ok";
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Rechazo Traslado de Activo Fijo'), $_REQUEST);
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
	
	function acepta_recepcion($acfi_id) {
		$this->layout = 'ajax';
		
		if ($this->ActivoFijo->aceptaRecepcion($acfi_id)) {
			// manda mail solo si existe mail de recepcion
			list($vars, $responsables) = $this->ActivoFijo->infoMailAceptaRecepcion($acfi_id);
			$vars['http_host'] = $_SERVER['HTTP_HOST'];
			$vars['mail_responsable'] = $responsables;
			
			if (is_array($responsables) && sizeof($responsables) > 0) {		
				if ($this->sendMail($vars['mail_responsable'], utf8_encode("Recepción exitosa (activo fijo)"), "acepta_recepcion_acfi", $vars)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Recepción Traslado de Activo Fijo'), $_REQUEST);
					$this->Session->setFlash(__(utf8_encode('La recepción ha sido exitosa'), true));
					$this->redirect(array('action' => 'index_entrada'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo enviar el correo electrónico de recepción.'), true));
					$this->redirect(array('action' => 'index_entrada'));
				}
			} else {
				$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Recepción Traslado de Activo Fijo'), $_REQUEST);
				$this->Session->setFlash(__(utf8_encode('La recepción ha sido exitosa'), true));
				$this->redirect(array('action' => 'index_entrada'));
			}
		} else {
			$this->Session->setFlash(__(utf8_encode('La recepción ha fallado. Por favor, inténtelo nuevamente'), true));
			$this->redirect(array('action' => 'index_entrada'));
		}
	}
	
	function plancheta() {
		$centros_costos = $this->Session->read('userdata.selectCC');
		$this->set('centros_costos', $centros_costos);
	}
	
	function genera_codigo_barra_masivo() {
		$this->layout = 'ajax';
			
		if (!class_exists("Imagick")) {
			$this->Session->setFlash(__(utf8_encode('No existe la extensión Imagick. Consulte al administrador del sistema.'), true));
			$this->redirect(array('action' => 'codigos_barra'));
		}
		
		$ceco_id = $this->Session->read('userdata.CentroCosto.ceco_id');
		$this->UbicacionActivoFijo->recursive = 2;
		$info = $this->UbicacionActivoFijo->find('all', array('fields' => array('UbicacionActivoFijo.*'), 'conditions' => array('UbicacionActivoFijo.ceco_id' => $ceco_id)));
		$conf = $this->Configuracion->obtieneCodBarraConf();
		
		try {
			$imgs = $this->ActivoFijo->generaImgCodBarra($info, $conf);
		} catch (Exception $e) {
			$this->Session->setFlash(__(utf8_encode($e->getMessage()), true));
			$this->redirect(array('action' => 'codigos_barra'));
		}
		
		$pdf = new FPDF('L', 'mm');
		
		// para codigos de barra en una sola pagina
		
		for ($i=0; $i<sizeof($imgs); $i++) {
			$pdf->AddPage('L', array($conf['barcode_height']*0.26, $conf['barcode_width']*0.24));
			$pdf->Image("http://".$_SERVER['HTTP_HOST'].$imgs[$i], 0, 0, 0, 0, 'PNG');
		}
		
		
		// 2 codigos de barra en una pagina
		/*for ($i=0; $i<sizeof($imgs); $i=$i+2) {
			$j = $i;
			$pdf->AddPage('L', array($conf['barcode_height']*0.27, $conf['barcode_width']*0.27*2));
			$pdf->Image("http://".$_SERVER['HTTP_HOST'].$imgs[$j], 0, 0, 0, 0, 'PNG');
			
			if (isset($imgs[$j+1])) {
				$pdf->Image("http://".$_SERVER['HTTP_HOST'].$imgs[$j+1], 105, 0, 0, 0, 'PNG');
			}
		}*/
		
		ob_clean();
		// para descarga
		$pdf->Output("codigos_barra_".$ceco_id.".pdf", "D");
		// para salida al browser
		//$pdf->Output();
	}

	function genera_codigo_barra_masivo_103($numero) {		
		$this->layout = 'ajax';
			
		if (!class_exists("Imagick")) {
			$this->Session->setFlash(__(utf8_encode('No existe la extensión Imagick. Consulte al administrador del sistema.'), true));
			$this->redirect(array('action' => 'codigos_barra'));
		}
		
		$ceco_id = $this->Session->read('userdata.CentroCosto.ceco_id');
		$this->UbicacionActivoFijo->recursive = 2;
		$info = $this->UbicacionActivoFijo->find('all', array('fields' => array('UbicacionActivoFijo.*'), 'conditions' => array('UbicacionActivoFijo.ceco_id' => $ceco_id)));
		$conf = $this->Configuracion->obtieneCodBarraConf();
		
		try {
			$imgs = $this->ActivoFijo->generaImgCodBarra($info, $conf);
		} catch (Exception $e) {
			$this->Session->setFlash(__(utf8_encode($e->getMessage()), true));
			$this->redirect(array('action' => 'codigos_barra'));
		}
		
		$pdf = new FPDF('L', 'mm');
		$cantidadPagina = 0;
		$altoImagen = 17;
		$anchoImagen = 10;
		$cantidadEtiquetaVacia = 1;
		for ($num = 2; $num <= $numero; $num++) {
			if ($num == 2) {
				$pdf->AddPage('P', 'Letter', array($conf['barcode_height']*0.27, $conf['barcode_width']*0.27*3));			
			}
			$cantidadPagina++;				
			
			if ($cantidadEtiquetaVacia == 3) {
				$cantidadEtiquetaVacia = 1;
				$altoImagen += 24.9;	
			} else {
				$cantidadEtiquetaVacia++;
			}			
				
			if ($cantidadPagina >= 30) {
				$cantidadPagina = 0;
				$altoImagen = 17;
			}		
		}
	
		for ($i=0; $i<sizeof($imgs); $i++) {
			$j = $i;
			
			if ($cantidadPagina == 0) {
				$pdf->AddPage('P', 'Letter', array($conf['barcode_height']*0.27, $conf['barcode_width']*0.27*3));			
			}
			
			if ($cantidadEtiquetaVacia == 1) {
				$anchoImagen = 10;
			} else if ($cantidadEtiquetaVacia == 2) {
				$anchoImagen = 80;
			} else if ($cantidadEtiquetaVacia == 3) {
				$anchoImagen = 153;
			}
			
			if (isset($imgs[$j])) {
				$pdf->Image("http://".$_SERVER['HTTP_HOST'].$imgs[$j], $anchoImagen, $altoImagen, 55, 0, 'PNG');
			}
			
			$cantidadPagina++;		
			if ($cantidadEtiquetaVacia == 3) {
				$cantidadEtiquetaVacia = 1;
				$altoImagen += 24.9;	
			} else {
				$cantidadEtiquetaVacia++;
			}		
			
			if ($cantidadPagina >= 30) {
				$cantidadPagina = 0;
				$altoImagen = 17;
			}										
		}

// 		FORMATO SIN POSICION
//		for ($i=0; $i<sizeof($imgs); $i=$i+3) {
//			$j = $i;
//			
//			if ($cantidadPagina == 0) {
//				$pdf->AddPage('P', 'Letter', array($conf['barcode_height']*0.27, $conf['barcode_width']*0.27*3));			
//			}
//			
//			$pdf->Image("http://".$_SERVER['HTTP_HOST'].$imgs[$j], 13, $altoImagen, 55, 0, 'PNG');
//			
//			if (isset($imgs[$j+1])) {
//				$pdf->Image("http://".$_SERVER['HTTP_HOST'].$imgs[$j+1], 85, $altoImagen, 55, 0, 'PNG');
//			}
//			
//			if (isset($imgs[$j+2])) {
//				$pdf->Image("http://".$_SERVER['HTTP_HOST'].$imgs[$j+2], 158, $altoImagen, 55, 0, 'PNG');
//			}
//			
//			$cantidadPagina += 3;	
//			$altoImagen += 25.8;		
//			if ($cantidadPagina >= 30) {
//				$cantidadPagina = 0;
//				$altoImagen = 5;
//			}											
//		}
		
		ob_clean();
		// para descarga
		$pdf->Output("codigos_barra_66_x_25.pdf", "D");
		// para salida al browser
		//$pdf->Output('', 'I');
	}
	
	function generar_etiquetas_103() {
		$numero = array();
		for ($i = 1; $i <= 30; $i++) {
			$numero[$i] = $i;
		}
		$this->set('numero', $numero);
	}
	
	function delete_codigo_barra($id = null) {		
		$dataSource = $this->UbicacionActivoFijo->getDataSource();
		$dataSource->begin($this->UbicacionActivoFijo);
		
		// Buscamos el email de todos los administradores
		$usuarios = $this->Usuario->find('all', array('fields' => 'Usuario.usua_email', 'conditions' => array('Usuario.perf_id' => 1)));			
		// Rescatamos datos del producto que se eliminara
		$datos_producto = $this->UbicacionActivoFijo->find('first', array('conditions' => array('UbicacionActivoFijo.ubaf_codigo' => $id)));
		$prod_nombre = $datos_producto['Producto']['prod_nombre'];
		$prod_codigo = $datos_producto['UbicacionActivoFijo']['ubaf_codigo'];		
		$ceco_nombre = $this->Session->read('userdata.CentroCosto.ceco_nombre');
		
		$vars = array(
			'prod_nombre' => $prod_nombre,
			'prod_codigo' => $prod_codigo,
			'ceco_nombre' => $ceco_nombre
		);
	
		$usua_email = array();
		foreach ($usuarios as $row) {
			$usua_email[] = $row['Usuario']['usua_email'];
		}
		
		$traf_id = $this->ActivoFijo->maxTrazabilidad();
	
		$data = array(
			'TrazabilidadActivoFijo' => array(
				'traf_id' => $traf_id,
				'traf_codigo' => $datos_producto['UbicacionActivoFijo']['ubaf_codigo'],
				'ceco_id' => $datos_producto['UbicacionActivoFijo']['ceco_id'],
				'prod_id' => $datos_producto['UbicacionActivoFijo']['prod_id'],
				'prop_id' => $datos_producto['UbicacionActivoFijo']['prop_id'],
				'situ_id' => $datos_producto['UbicacionActivoFijo']['situ_id'],
				'marc_id' => $datos_producto['UbicacionActivoFijo']['marc_id'],
				'colo_id' => $datos_producto['UbicacionActivoFijo']['colo_id'],
				'mode_id' => $datos_producto['UbicacionActivoFijo']['mode_id'],
				'esre_id' => 2, // Deshabilitado
				'tmov_id' => 4, // Eliminación
				'traf_fecha_garantia' => $datos_producto['UbicacionActivoFijo']['ubaf_fecha_garantia'],
				'traf_precio' => $datos_producto['UbicacionActivoFijo']['ubaf_precio'],
				'traf_depreciable' => $datos_producto['UbicacionActivoFijo']['ubaf_depreciable'],
				'traf_vida_util' => $datos_producto['UbicacionActivoFijo']['ubaf_vida_util'],
				'traf_fecha_adquisicion' => $datos_producto['UbicacionActivoFijo']['ubaf_fecha_adquisicion'],
				'traf_serie' => $datos_producto['UbicacionActivoFijo']['ubaf_serie']
			)
		);

		if ($this->UbicacionActivoFijo->delete($id)) {
				
			$this->TrazabilidadActivoFijo->save($data['TrazabilidadActivoFijo']);
			if (sizeof($usua_email) > 0 && is_array($usua_email)) {
				if ($this->sendMail($usua_email, utf8_encode("Eliminación de Activo Fijo"), "elimina_bien_activo_fijo", $vars)) {				
					// Enviamos email a los administradores del sistema.
					$dataSource->commit($this->UbicacionActivoFijo);
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación Código de barra'), $_REQUEST);
					$this->Session->setFlash(__(utf8_encode('Código de barra eliminado'), true));
					$this->redirect(array('action' => 'codigos_barra'));								
				} else {
					// No se envia email pero si se elimina por completo el bien.
					$dataSource->commit($this->UbicacionActivoFijo);
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación Código de barra'), $_REQUEST);
					$this->Session->setFlash(__(utf8_encode('Código de barra eliminado'), true));
					$this->redirect(array('action' => 'codigos_barra'));
				}
			} else {
				$dataSource->commit($this->UbicacionActivoFijo);
				$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación Código de barra'), $_REQUEST);
				$this->Session->setFlash(__(utf8_encode('Código de barra eliminado'), true));
				$this->redirect(array('action' => 'codigos_barra'));
			}		
		} else {
			$dataSource->rollback($this->UbicacionActivoFijo);
			$this->Session->setFlash(__(utf8_encode('El código de barra no se pudo eliminar'), true));
			$this->redirect(array('action' => 'codigos_barra'));
		}
	}
	
	function searchAll($tmov_id = 1) {
		$this->layout = 'ajax';
		$string = stripslashes($_GET['term']);
		
		//buscamos activos fijos
		$ceco_id = $this->Session->read('userdata.CentroCosto.ceco_id');
		$info = $this->ActivoFijo->searchAll($string, $ceco_id, $tmov_id);
		
		$activos_fijos = array();
		
		if (sizeof($info) > 0) {
			foreach ($info as $acfi) {
				$acfi = array_pop($acfi);
				$value = sprintf("%012d", $acfi['acfi_correlativo'])." - ".date("d-m-Y H:i:s", strtotime($acfi['acfi_fecha']));
				$activos_fijos[] = array('value' => $value, 'label' => $value, 'acfi_id' => $acfi['acfi_id']);
			}
		}
		$this->set('json_info', json_encode($activos_fijos));
		
	}
	
	function carga_masiva() {
		if (!empty($this->data)) {
			
			if (is_uploaded_file($this->data['CargaMasiva']['archivo']['tmp_name'])) {
				$cmHost = Configure::read('cmHost');
				$cmPort = Configure::read('cmPort');
				
				//$fp = fsockopen("192.168.1.150", 1212, $errno, $errstr, 100);
				$fp = stream_socket_client("tcp://".$cmHost.":".$cmPort, $errno, $errstr, 100);
					
				if ($fp) {
					$db = ConnectionManager::getDataSource('default')->config;
					$dbHost = $db['host'];
					$dbUser = $db['login'];
					$dbPass = $db['password'];
					$dbName = $db['database'];
					
					//pasamos el archivo a base64
					$csv = file_get_contents($this->data['CargaMasiva']['archivo']['tmp_name']);
					$csv = base64_encode($csv);
					
					$data = array("dbHost" => $dbHost, "dbUser" => $dbUser, "dbPass" => $dbPass, "dbName" => $dbName, "csv" => $csv);
					$command = array("comando" => "uploadCSV"
									,"data"    => $data);
					
					
					fwrite($fp, json_encode($command)."\n");
					
					$resp = "";
					while (($buffer = fgets($fp)) !== false) {
						$resp .= nl2br(utf8_encode($buffer));
					}
					
					fclose($fp);
					$this->set("resp", $resp);
					
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo conectar al servidor de carga. Por favor contactar con el administrador del sistema.'), true));
					$this->redirect(array('action' => 'carga_masiva'));
				}
			} else {
				$this->Session->setFlash(__(utf8_encode('Error, el archivo subido se encuentra corrupto o no corresponde a un formato válido.'), true));
				$this->redirect(array('action' => 'carga_masiva'));
			}
		}
	}
	
	function download_fotografia($acfd_id) {
		$this->layout = 'ajax';		
		$this->ActivoFijoDocumento->id = $acfd_id;
		
		if (!$this->ActivoFijoDocumento->exists()) {
			throw new NotFoundException(__('Id inválido'));
		}
		
		$base = $this->ActivoFijoDocumento->read(null, $acfd_id);
		$base['ActivoFijoDocumento']['acfd_nombre'] = str_replace(" ", "_", $base['ActivoFijoDocumento']['acfd_nombre']);
		
		ob_clean();
		header('Content-type: '.$base['ActivoFijoDocumento']['acfd_type']);
		//header('Content-Disposition: attachment; filename='.$base['ActivoFijoDocumento']['acfd_nombre']);
		
		echo base64_decode($base['ActivoFijoDocumento']['acfd_archivo']);
	}

	function find_parametro_iva() {
		$this->autoRender = false;
		$param_iva = $this->Configuracion->find('first', array('conditions' => array('Configuracion.conf_id' => 'param_iva')));
		
		$valor_iva = 0;
		if (sizeof($param_iva) > 0 && is_array($param_iva)) {
			$valor_iva = $param_iva['Configuracion']['conf_valor'];
		}
		
		$data['valor'] = $valor_iva;
		echo json_encode($data);
	}
	
}
?>
