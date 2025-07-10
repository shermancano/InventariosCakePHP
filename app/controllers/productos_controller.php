<?php
class ProductosController extends AppController {
	var $name = 'Productos';
	var $uses = array('Producto', 'ProductoImagen');
	
	function index() {
		$this->Producto->recursive = 2;		
		$productos = $this->paginate();
		foreach ($productos as $key => $prod) {
			$countProductoImagen = $this->ProductoImagen->find('count', array('conditions' => array('ProductoImagen.prod_id' => $prod['Producto']['prod_id'])));			
			$productos[$key]['ProductoImagen']['prod_imagen'] = $countProductoImagen;
		}
		$this->set('productos', $productos);
	}
	
	function add() {
		if (!empty($this->data)) {
			$this->Producto->set($this->data);
			
			if ($this->Producto->validates()) {				
				if ($this->Producto->save($this->data['Producto'])) {
					$prod_id = $this->Producto->id;
					
					if ($this->data['ProductoImagen']['prod_contenido']['error'] == 0) {				
						$tmp = $this->data['ProductoImagen']['prod_contenido']['tmp_name'];
						$fp = fopen($tmp, "r");
						$bin = fread($fp, filesize($tmp));
						$bin = base64_encode($bin);
						fclose($fp);
					
						$this->data['ProductoImagen']['prod_id'] = $prod_id;
						$this->data['ProductoImagen']['prim_nombre'] = $this->data['ProductoImagen']['prod_contenido']['name'];
						$this->data['ProductoImagen']['prim_archivo'] = $bin;
						$this->data['ProductoImagen']['prim_tipo'] = $this->data['ProductoImagen']['prod_contenido']['type'];
						$this->data['ProductoImagen']['prim_size'] = $this->data['ProductoImagen']['prod_contenido']['size'];						
						$this->ProductoImagen->save($this->data['ProductoImagen']);	
					}
					
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), 'Nuevo Producto', $_REQUEST);
					$this->Session->setFlash(__('El producto ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el producto, por favor inténtelo nuevamente'), true));
				}
			}
		}
		
		$tipos_bienes = $this->Producto->TipoBien->find('list', array('fields' => array('tibi_id', 'tibi_nombre'), 'order' => 'tibi_nombre ASC'));
		$unidades = $this->Producto->Unidad->find('list', array('fields' => array('unid_id', 'unid_nombre'), 'order' => 'unid_nombre ASC'));
		$tipos_familias = $this->Producto->Grupo->Familia->TipoFamilia->find('list', array('fields' => array('tifa_id', 'tifa_nombre'), 'order' => 'tifa_id ASC'));
		$this->set('tipos_bienes', $tipos_bienes);
		$this->set('unidades', $unidades);
		$this->set('tipos_familias', $tipos_familias);
	}
	
	function edit($prod_id) {
		if (!$prod_id && empty($this->data)) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action' => 'index'));
		}
		
		if (!empty($this->data)) {
			$this->Producto->set($this->data);
			
			if ($this->Producto->validates()) {
				if ($this->Producto->save($this->data['Producto'])) {
					$prod_id = $this->Producto->id;
					
					if ($this->data['ProductoImagen']['prod_contenido']['error'] == 0) {				
						$tmp = $this->data['ProductoImagen']['prod_contenido']['tmp_name'];
						$fp = fopen($tmp, "r");
						$bin = fread($fp, filesize($tmp));
						$bin = base64_encode($bin);
						fclose($fp);
					
						$this->data['ProductoImagen']['prod_id'] = $prod_id;
						$this->data['ProductoImagen']['prim_nombre'] = $this->data['ProductoImagen']['prod_contenido']['name'];
						$this->data['ProductoImagen']['prim_archivo'] = $bin;
						$this->data['ProductoImagen']['prim_tipo'] = $this->data['ProductoImagen']['prod_contenido']['type'];
						$this->data['ProductoImagen']['prim_size'] = $this->data['ProductoImagen']['prod_contenido']['size'];												
						$this->ProductoImagen->save($this->data['ProductoImagen']);	
					}
					
					$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Modificación Producto'), $_REQUEST);
					$this->Session->setFlash(__('El producto ha sido guardado', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__(utf8_encode('No se pudo guardar el producto, por favor inténtelo nuevamente'), true));
				}
			}
		}
		
		if (empty($this->data)) {
			$this->Producto->recursive = 2;
			$this->data = $this->Producto->read(null, $prod_id);
			$prim_id = $this->ProductoImagen->find('first', array('fields' => array('ProductoImagen.prim_id', 'ProductoImagen.prod_id'), 'conditions' => array('ProductoImagen.prod_id' => $prod_id)));						
			$this->set('prim_id', $prim_id);
		}
		
		$familias = $this->Producto->Grupo->Familia->find('list', array('fields' => array('fami_id', 'fami_nombre'), 'order' => 'fami_nombre ASC'));
		$tipos_bienes = $this->Producto->TipoBien->find('list', array('fields' => array('tibi_id', 'tibi_nombre'), 'order' => 'tibi_nombre ASC'));
		$unidades = $this->Producto->Unidad->find('list', array('fields' => array('unid_id', 'unid_nombre'), 'order' => 'unid_nombre ASC'));
		$grupos = $this->Producto->Grupo->find('list', array('fields' => array('grup_id', 'grup_nombre'), 'order' => 'grup_nombre ASC'));
		$tipos_familias = $this->Producto->Grupo->Familia->TipoFamilia->find('list', array('fields' => array('tifa_id', 'tifa_nombre'), 'order' => 'tifa_id ASC'));
		
		$this->set('familias', $familias);
		$this->set('tipos_bienes', $tipos_bienes);
		$this->set('unidades', $unidades);
		$this->set('grupos', $grupos);
		$this->set('tipos_familias', $tipos_familias);		
	}
	
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('ID invalido', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Producto->delete($id)) {
			$this->Log->write($this->Session->read('userdata.Usuario.usua_id'), utf8_encode('Eliminación Producto'), $_REQUEST);
			$this->Session->setFlash(__('Producto eliminado', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('No se pudo eliminar el producto', true));
		$this->redirect(array('action' => 'index'));
	}
	
	function searchExistencias() {
		$this->layout = 'ajax';
		$string = stripslashes($_GET['term']);
		//buscamos existencias
		$tibi_id = 3;
		$info = $this->Producto->searchProducto($string, $tibi_id);
		$productos = array();
		
		if (sizeof($info) > 0) {
			foreach ($info as $producto) {
				$producto = array_pop($producto);
				$productos[] = array('value' => $producto['prod_nombre'], 'label' => $producto['prod_nombre'], 'prod_id' => $producto['prod_id']);
			}
		}
		$this->set('json_info', json_encode($productos));
	}
	
	function searchFungibles() {
		$this->layout = 'ajax';
		$string = stripslashes($_GET['term']);
		//buscamos fungibles
		$tibi_id = 2;
		$info = $this->Producto->searchProducto($string, $tibi_id);
		$productos = array();
		
		if (sizeof($info) > 0) {
			foreach ($info as $producto) {
				$producto = array_pop($producto);
				$productos[] = array('value' => $producto['prod_nombre'], 'label' => $producto['prod_nombre'], 'prod_id' => $producto['prod_id']);
			}
		}
		$this->set('json_info', json_encode($productos));
	}
	
	function searchActivosFijos() {
		$this->layout = 'ajax';
		$string = stripslashes($_GET['term']);
		//buscamos activos fijos
		$tibi_id = 1;
		$info = $this->Producto->searchProducto($string, $tibi_id);
		$productos = array();
		
		if (sizeof($info) > 0) {
			foreach ($info as $producto) {
				$producto = array_pop($producto);
				$productos[] = array('value' => $producto['prod_nombre'], 'label' => $producto['prod_nombre'], 'prod_id' => $producto['prod_id']);
			}
		}
		$this->set('json_info', json_encode($productos));
	}
	
	function searchTodo() {
		$this->layout = 'ajax';
		$string = stripslashes($_GET['term']);
		//buscamos todo
		$info = $this->Producto->searchProducto($string);
		$productos = array();
		
		if (sizeof($info) > 0) {
			foreach ($info as $producto) {
				$producto = array_pop($producto);
				$productos[] = array('value' => $producto['prod_nombre'], 'label' => $producto['prod_nombre'], 'prod_id' => $producto['prod_id']);
			}
		}
		$this->set('json_info', json_encode($productos));
	}
	
	function view_imagen($prod_id = null) {
		$this->layout = 'ajax';		
		$base = $this->ProductoImagen->find('first', array('conditions' => array('ProductoImagen.prod_id' => $prod_id)));
		
		ob_clean();
		header('Content-type: '.$base['ProductoImagen']['prim_tipo']);
		// Se comenta header para que se muestre el docuemento en el navegador
		//header('Content-Disposition: attachment; filename='.$base['Documento']['docu_nombre_original']);
		
		echo base64_decode($base['ProductoImagen']['prim_archivo']);
	}
}

?>
