<?php
class UsuariosController extends AppController {
	var $name = 'Usuarios';
	var $uses = array('Usuario', 'Responsable', 'Perfil', 'UbicacionActivoFijo', 'ActivoFijo', 'DetalleActivoFijo', 'Configuracion');

	function index() {
		$this->Usuario->recursive = 0;

		if (!empty($this->data)) {
			$this->params['named']['page'] = 1;
			$criterio = trim($this->data['Usuario']['busqueda']);
			$opcion = $this->data['Usuario']['opcion'];
			$this->Session->write('userdata.criterio_usuario', $criterio);
			$this->Session->write('userdata.opcion_usuario', $opcion);

			if ($opcion == 1) {
				$conds = array(
					'AND' => array(
						'Usuario.usua_nombre ilike' => '%'.$criterio.'%'					
					)
				);
			} else {
				$conds = array(
					'AND' => array(
						'Usuario.usua_rut ilike' => '%'.$criterio.'%'					
					)
				);
			}
			
			$usuarios = $this->paginate('Usuario', $conds);
		} else {
			$criterio = '';
			$opcion = '';
			$conds = null;

			if (isset($this->params['named']['page'])) {				
				$criterio = $this->Session->read('userdata.criterio_usuario');
				$opcion = $this->Session->read('userdata.opcion_usuario');

				if (isset($criterio) && isset($opcion)) {
					if ($opcion == 1) {
						$conds = array(
							'AND' => array(
								'Usuario.usua_nombre ilike' => '%'.$criterio.'%'					
							)
						);
					} else {
						$conds = array(
							'AND' => array(
								'Usuario.usua_rut ilike' => '%'.$criterio.'%'					
							)
						);
					}
				}				
			} else {
				// Eliminamos session cuando se presiona paginate sin filtros (index)
				$this->Session->delete('userdata.criterio_usuario');
				$this->Session->delete('userdata.opcion_usuario');
			}
			
			$usuarios = $this->paginate('Usuario', $conds);
		}

		$this->set('usuarios', $usuarios);
		$this->set('criterio', $criterio);
		$this->set('opcion', $opcion);
	}

	function add() {
		if (!empty($this->data)) {
			$this->Usuario->create();
			$this->Usuario->set($this->data);
			
			if ($this->Usuario->validates()) {
				$this->data['Usuario']['usua_password'] = md5($this->data['Usuario']['usua_password']);
				$this->data['Usuario']['usua_password2'] = md5($this->data['Usuario']['usua_password2']);
				
				if ($this->Usuario->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Nuevo Usuario', $_REQUEST);
					$this->Session->setFlash(__('El usuario ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('El usuario no se pudo guardar. Por favor, inténtelo nuevamente.'), true));
				}
			}
		}
		$perfiles = $this->Usuario->Perfil->find('list', array('fields' => 'Perfil.perf_nombre'));
		$estados = $this->Usuario->EstadoRegistro->find('list', array('fields' => 'EstadoRegistro.esre_nombre'));
		$this->set('perfiles', $perfiles);
		$this->set('estados', $estados);
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->Usuario->set($this->data);
			
			$pass1 = trim($this->data['Usuario']['usua_password']);
			$pass2 = trim($this->data['Usuario']['usua_password2']);
			
			if ($pass1 != "" || $pass2 != "") {
				$this->Usuario->checkPasswords();
			}
			
			if ($this->Usuario->validates()) {
				if ($pass1 == "" || $pass2 == "") {
					$usua_id = $this->data['Usuario']['usua_id'];
					$usua_info = $this->Usuario->find('first', array('conditions' => array('usua_id' => $usua_id)));
					$usua_password = $usua_info['Usuario']['usua_password'];
					$this->data['Usuario']['usua_password'] = $usua_password;
					$this->data['Usuario']['usua_password2'] = $usua_password;
				} else {
					$this->data['Usuario']['usua_password'] = md5($pass1);
					$this->data['Usuario']['usua_password2'] = md5($pass2);
				}
				
				if ($this->Usuario->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación de Usuario'), $_REQUEST);
					$this->Session->setFlash(__('El usuario ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('El usuario no se pudo guardar. Por favor, inténtelo nuevamente.'), true));
				}
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Usuario->read(null, $id);
		}
		
		$perfiles = $this->Usuario->Perfil->find('list', array('fields' => 'Perfil.perf_nombre'));
		$estados = $this->Usuario->EstadoRegistro->find('list', array('fields' => 'EstadoRegistro.esre_nombre'));
		$this->set('perfiles', $perfiles);
		$this->set('estados', $estados);
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Usuario->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación de Usuario'), $_REQUEST);
			$this->Session->setFlash(__('Usuario eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('No se pudo eliminar el usuario', true));
		$this->redirect(array('action' => 'index'));
	}
	
	function micuenta() {
		if (!empty($this->data)) {
			$this->Usuario->set($this->data);
			
			$pass1 = trim($this->data['Usuario']['usua_password']);
			$pass2 = trim($this->data['Usuario']['usua_password2']);
			
			if ($pass1 != "" || $pass2 != "") {
				$this->Usuario->checkPasswords();
			}
			
			if ($this->Usuario->validates()) {
				if ($pass1 == "" || $pass2 == "") {
					$usua_id = $this->data['Usuario']['usua_id'];
					$usua_info = $this->Usuario->find('first', array('conditions' => array('usua_id' => $usua_id)));
					$usua_password = $usua_info['Usuario']['usua_password'];
					$this->data['Usuario']['usua_password'] = $usua_password;
					$this->data['Usuario']['usua_password2'] = $usua_password;
				} else {
					$this->data['Usuario']['usua_password'] = md5($pass1);
					$this->data['Usuario']['usua_password2'] = md5($pass2);
				}
				
				if ($this->Usuario->save($this->data)) {
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación Cuenta de Usuario'), $_REQUEST);
					$this->Session->setFlash(__('Datos modificados correctamente', true));
					$this->redirect(array('action' => 'micuenta'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudieron modificar los datos. Por favor, inténtelo nuevamente.'), true));
				}
			}
		}
		
		if (empty($this->data)) {
			$usua_id = $this->Session->read('userdata.Usuario.usua_id');
			$this->set('usua_id', $usua_id);
			$this->data = $this->Usuario->read(null, $usua_id);
			unset($this->data['Usuario']['usua_password']);
		} else {
			$this->set('usua_id', $this->data['Usuario']['usua_id']);
		}
	}
	
	function searchUsuarios() {
		$this->layout = 'ajax';
		$string = stripslashes($_GET['term']);
		$info = $this->Usuario->searchUsuario($string);
		$usuarios = array();
		
		if (sizeof($info) > 0) {
			foreach ($info as $usuario) {
				$usuario = array_pop($usuario);
				$usuarios[] = array('value' => $usuario['usua_nombre'], 'label' => $usuario['usua_nombre'], 'usua_id' => $usuario['usua_id']);
			}
		}
		$this->set('json_info', json_encode($usuarios));
	}
	
	function selCentroCosto($ceco_id = null) {
		
		if (!empty($ceco_id)) {
			$centro_costo = $this->Responsable->CentroCosto->read('CentroCosto.*', $ceco_id);
			$this->Session->write('userdata.CentroCosto', $centro_costo['CentroCosto']);
			$ceco_id = $centro_costo['CentroCosto']['ceco_id'];
			$arbol = $this->Responsable->CentroCosto->findAllChildren($ceco_id);
			$arbol_all = $this->Responsable->CentroCosto->findAll();
			$this->Session->write('userdata.selectCC', $this->ccArrayToHTML($arbol));
			$this->Session->write('userdata.arrayCC', $this->ccArrayToCcVector($arbol));
			$this->Session->write('userdata.selectCCAll', $this->ccArrayToHTML($arbol_all));
			
			$this->redirect(array('controller' => 'usuarios', 'action' => 'main'));
			exit;
		}
		
		$userdata = $this->Session->read('userdata');
		$usua_id = $userdata['Usuario']['usua_id'];
		$resp = $this->Responsable->find('first', array('fields' => array('CentroCosto.*'), 'conditions' => array('Responsable.usua_id' => $usua_id, 'Responsable.esre_id' => 1)));
		$ceco_id = $resp['CentroCosto']['ceco_id'];
		$ceco_nombre = $resp['CentroCosto']['ceco_nombre'];
		$centros_costos = $this->Responsable->CentroCosto->findAllChildren($ceco_id);
		$this->set('centros_costos', $centros_costos);
	}
	
	function filtro_index($criterio, $opcion, $arrayCentrosCostos) {
		if ($opcion == 1) { // Numero de factura
			$conds = array(
				'AND' => array(
					'UbicacionActivoFijo.ceco_id' => $arrayCentrosCostos					
				),
				'OR' => array(
					'ActivoFijo.acfi_nro_documento ilike' => '%'.$criterio.'%'						
				)
			);
		} else if ($opcion == 2) { // Orden de Compra
			$conds = array(
				'AND' => array(
					'UbicacionActivoFijo.ceco_id' => $arrayCentrosCostos					
				),
				'OR' => array(
					'ActivoFijo.acfi_orden_compra ilike' => '%'.$criterio.'%'						
				)
			);
		} else if ($opcion == 3) { // Codigo de barra
			$conds = array(
				'AND' => array(
					'UbicacionActivoFijo.ceco_id' => $arrayCentrosCostos					
				),
				'OR' => array(
					'UbicacionActivoFijo.ubaf_codigo ilike' => '%'.$criterio.'%'						
				)
			);
		} else if ($opcion == 4) { // Nombre de producto
			$conds = array(
				'AND' => array(
					'UbicacionActivoFijo.ceco_id' => $arrayCentrosCostos					
				),
				'OR' => array(						 
					'lower(Producto.prod_nombre) ilike' => '%'.strtolower($criterio).'%'
				)
			);
		} else if ($opcion == 5) { // Modelo
			$conds = array(
				'AND' => array(
					'UbicacionActivoFijo.ceco_id' => $arrayCentrosCostos					
				),
				'OR' => array(						 
					'lower(Modelo.mode_nombre) ilike' => '%'.strtolower($criterio).'%'
				)
			);
		} else if ($opcion == 6) { // Marca
			$conds = array(
				'AND' => array(
					'UbicacionActivoFijo.ceco_id' => $arrayCentrosCostos					
				),
				'OR' => array(						 
					'lower(Marca.marc_nombre) ilike' => '%'.strtolower($criterio).'%'
				)
			);
		} else if ($opcion == 7) { // Color
			$conds = array(
				'AND' => array(
					'UbicacionActivoFijo.ceco_id' => $arrayCentrosCostos					
				),
				'OR' => array(						 
					'lower(Color.colo_nombre) ilike' => '%'.strtolower($criterio).'%'
				)
			);
		} else if ($opcion == 8) { // Serie
			$conds = array(
				'AND' => array(
					'UbicacionActivoFijo.ceco_id' => $arrayCentrosCostos					
				),
				'OR' => array(
					'UbicacionActivoFijo.ubaf_serie ilike' => '%'.$criterio.'%'						
				)
			);
		} else if ($opcion == 9) { // Financiamiento
			$conds = array(
				'AND' => array(
					'UbicacionActivoFijo.ceco_id' => $arrayCentrosCostos					
				),
				'OR' => array(
					'Financiamiento.fina_nombre ilike' => '%'.$criterio.'%'						
				)
			);
		} else if ($opcion == 10) { // Nro resolucion
			$conds = array(
				'AND' => array(
					'UbicacionActivoFijo.ceco_id' => $arrayCentrosCostos					
				),
				'OR' => array(
					'ActivoFijo.acfi_numero_resolucion ilike' => '%'.$criterio.'%'						
				)
			);
		} else if ($opcion == 11) { // Nro resolucion
			$conds = array(
				'AND' => array(
					'UbicacionActivoFijo.ceco_id' => $arrayCentrosCostos					
				),
				'OR' => array(
					'Proveedor.prov_nombre ilike' => '%'.$criterio.'%'						
				)
			);
		}

		return $conds;
	}	

	function main() {
		$this->UbicacionActivoFijo->recursive = 0;	
		$ceco_id = $this->Session->read('userdata.CentroCosto.ceco_id');
		$arrayCentrosCostos = array();
		$cc_hijos = $this->ccArrayToCcVector($this->Responsable->CentroCosto->findAllChildren($ceco_id));
		$arrayCentrosCostos = $cc_hijos;

		if (!empty($this->data)) {
			$this->params['named']['page'] = 1;
			$criterio = trim($this->data['ActivoFijo']['busqueda']);
			$opcion = $this->data['ActivoFijo']['opcion'];
			$this->Session->write('userdata.criterio', $criterio);
			$this->Session->write('userdata.opcion', $opcion);

			$conds = $this->filtro_index($criterio, $opcion, $arrayCentrosCostos);
		} else {
			$criterio = '';
			$opcion = '';
			$conds = array(
				'AND' => array(
					'UbicacionActivoFijo.ceco_id' => $arrayCentrosCostos					
				)
			);

			if (isset($this->params['named']['page'])) {				
				$criterio = $this->Session->read('userdata.criterio');
				$opcion = $this->Session->read('userdata.opcion');

				if (isset($criterio) && isset($opcion)) {
					$conds = $this->filtro_index($criterio, $opcion, $arrayCentrosCostos);
				}				
			} else {
				// Eliminamos session cuando se presiona paginate sin filtros (index)
				$this->Session->delete('userdata.criterio');
				$this->Session->delete('userdata.opcion');
			}
		}

		$this->paginate = array(
			'joins' => array(
				array(
					'alias' => 'DetalleActivoFijo',
					'table' => 'detalle_activos_fijos',
					'type' => 'inner',
					'conditions' => array(
						'DetalleActivoFijo.deaf_codigo = UbicacionActivoFijo.ubaf_codigo'							
					)
				),
				array(
					'alias' => 'ActivoFijo',
					'table' => 'activos_fijos',
					'type' => 'inner',
					'conditions' => array(
						'DetalleActivoFijo.acfi_id = ActivoFijo.acfi_id'							
					)
				),
				array(
					'alias' => 'Financiamiento',
					'table' => 'financiamientos',
					'type' => 'left',
					'conditions' => array(
						'ActivoFijo.fina_id = Financiamiento.fina_id'							
					)
				),
				array(
					'alias' => 'Proveedor',
					'table' => 'proveedores',
					'type' => 'left',
					'conditions' => array(
						'ActivoFijo.prov_id = Proveedor.prov_id'							
					)
				)
			),
			'fields' => array(
				'UbicacionActivoFijo.ubaf_codigo',
				'CentroCosto.ceco_id',
				'CentroCosto.ceco_nombre',
				'Producto.prod_id',
				'Producto.prod_nombre',
				'ActivoFijo.acfi_id',
				'ActivoFijo.acfi_nro_documento',
				'ActivoFijo.acfi_orden_compra',
				'Modelo.mode_nombre',
				'Marca.marc_nombre',
				'Color.colo_nombre',
				'UbicacionActivoFijo.ubaf_serie',
				'Financiamiento.fina_nombre',
				'Proveedor.prov_nombre'
			),
			'conditions' => $conds
		);
		$detalles = $this->paginate('UbicacionActivoFijo');		
		$this->set('detalles', $detalles);
		$userdata = $this->Session->read('userdata');
		//Obtenemos datos de perfil de usuario
		$perfil_nom = $this->Session->read('userdata.Perfil.perf_nombre');
		$this->set('perfil_nom', $perfil_nom);
		//Obtenemos datos de ultimo acceso
		$ultimo_acceso = $this->Session->read('userdata.Usuario.usua_ultimo_acceso');
		$this->set('ultimo_acceso', $ultimo_acceso);	
		$this->set('criterio', $criterio);
		$this->set('opcion', $opcion);	
	}

	function view_busqueda($id = null, $codigo_barra = null) {
		if (!$id) {
			$this->Session->setFlash(__('Id invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		
		$this->ActivoFijo->recursive = 0;
		$entradas = $this->ActivoFijo->read(null, $id);
		$ceco_id = $entradas['ActivoFijo']['ceco_id'];
		$this->set('entrada', $entradas);
		
		$detalles = $this->paginate('DetalleActivoFijo', 
			array(
				'AND' => array(
					'DetalleActivoFijo.acfi_id' => $id,
					'DetalleActivoFijo.deaf_codigo' => $codigo_barra
				)
			)
		);
		$this->set('detalles', $detalles);
		
		$total = $this->ActivoFijo->DetalleActivoFijo->find('all', 
			array(
				'fields' => array(
					'sum(deaf_precio) as total'), 
				'conditions' => array(
					'DetalleActivoFijo.acfi_id' => $id
				)
			)
		);
	
		$this->set('total', $total[0][0]['total']);
		
		$param_iva = $this->Configuracion->find('first', 
			array(
				'conditions' => array(
					'Configuracion.conf_id' => 'param_iva'
				)
			)
		);
		
		if (sizeof($param_iva) > 0 && is_array($param_iva)) {
			$valor_iva = $param_iva['Configuracion']['conf_valor'];
		} else {
			$valor_iva = 0;
		}
		
		$ubicacionEntrada = $this->UbicacionActivoFijo->CentroCosto->findUbicacion($ceco_id);				
		$ubicacion = "/".str_replace(" / ", " / ", $ubicacionEntrada);		
		//$ubicacion = basename(dirname($ubicacion))."/".basename($ubicacion);		

	
		if (strpos($ubicacionEntrada, "/") == 0) $ubicacionEntrada = substr($ubicacionEntrada, 1);
		$ubicacionEntrada = utf8_decode($ubicacionEntrada);
		
		$ubicacionPadre = '';
		if (!empty($entradas['ActivoFijo']['ceco_id_padre'])) {
			$ceco_id_padre = $entradas['ActivoFijo']['ceco_id_padre'];
			$ubicacionPadre = $this->UbicacionActivoFijo->CentroCosto->findUbicacion($ceco_id_padre);				
			$ubicacionPadre = "/".str_replace(" / ", " / ", $ubicacionPadre);			
		 
			if (strpos($ubicacionPadre, "/") == 0) $ubicacionPadre = substr($ubicacionPadre, 1);
			$ubicacionPadre = utf8_decode($ubicacionPadre);
		}

		// Buscamos bien
		$findBien = $this->UbicacionActivoFijo->find('first',
			array(
				'fields' => array(
					'UbicacionActivoFijo.ubaf_codigo',
					'UbicacionActivoFijo.ceco_id'
				),
				'conditions' => array(
					'UbicacionActivoFijo.ubaf_codigo' => $codigo_barra
				)
			)
		);

		$ceco_id_bien = $findBien['UbicacionActivoFijo']['ceco_id'];
		$ubicacionBien = $this->UbicacionActivoFijo->CentroCosto->findUbicacion($ceco_id_bien);				
		$ubicacionBien = "/".str_replace(" / ", " / ", $ubicacionBien);			
	 
		if (strpos($ubicacionBien, "/") == 0) $ubicacionBien = substr($ubicacionBien, 1);
		$ubicacionBien = utf8_decode($ubicacionBien);
		
		$this->set('valor_iva', $valor_iva);
		$this->set('ubicacionEntrada', $ubicacionEntrada);
		$this->set('ubicacionPadre', $ubicacionPadre);
		$this->set('ubicacionBien', $ubicacionBien);
		$this->set('id', $id);
	}
}
?>
