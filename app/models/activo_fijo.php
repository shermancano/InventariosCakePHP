<?php
class ActivoFijo extends AppModel {
	var $name = 'ActivoFijo';
	var $primaryKey = 'acfi_id';
	var $useTable = 'activos_fijos';
	
	var $belongsTo = array(
		'CentroCosto' => array(
			'className' => 'CentroCosto',
			'foreignKey' => 'ceco_id'
		),
		'CentroCostoPadre' => array(
			'className' => 'CentroCosto',
			'foreignKey' => 'ceco_id_padre'
		),
		'CentroCostoHijo' => array(
			'className' => 'CentroCosto',
			'foreignKey' => 'ceco_id_hijo'
		),
		'TipoDocumento' => array(
			'className' => 'TipoDocumento',
			'foreignKey' => 'tido_id'
		),
		'Proveedor' => array(
			'className' => 'Proveedor',
			'foreignKey' => 'prov_id'
		),
		'ActivoFijoPadre' => array(
			'className' => 'ActivoFijo',
			'foreignKey' => 'acfi_id_padre'
		),
		'Financiamiento' => array(
			'className' => 'Financiamiento',
			'foreignKey' => 'fina_id'
		),
		'TipoResolucion' => array(
			'className' => 'TipoResolucion',
			'foreignKey' => 'tire_id'
		)
	);
	
	var $hasMany = array(
	    'DetalleActivoFijo' => array('className' => 'DetalleActivoFijo',
									 'foreignKey' => 'acfi_id',
									 'order'      => 'deaf_codigo'),
		'RechazoActivoFijo' => array('className' => 'RechazoActivoFijo',
									 'foreignKey' => 'acfi_id')
	);
	
	var $hasOne = array(
	    'ActivoFijoDocumento' => array('className' => 'ActivoFijoDocumento',
									 'foreignKey' => 'acfi_id')
	);
	
	var $validate = array(
		/*'prov_id' => array('rule' => 'numeric', 'required' => true, 'message' => 'Debe seleccionar el proveedor')*/
	   'fina_id' => array('rule' => 'numeric', 'required' => true, 'message' => 'Debe seleccionar la fuente de financiamiento')
	);
	
	function obtieneCorrelativo($ceco_id, $tmov_id) {
		$sql = "select coalesce(max(acfi_correlativo), 0) + 1 as acfi_correlativo
				from activos_fijos
				where ceco_id = ".$ceco_id."
				and   tmov_id = ".$tmov_id;
		$res = $this->query($sql); 
		
		return $res[0][0]['acfi_correlativo'];
	}
	
	function generaCodBarraBase($prod_id) {
		$prod_info = $this->DetalleActivoFijo->Producto->find('first', array('conditions' => array('Producto.prod_id' => $prod_id)));
		
		$fami_id = $prod_info['Grupo']['fami_id'];
		$grup_id = $prod_info['Grupo']['grup_id'];
		
		//familia + grupo + id_producto 
		//000 0000 0000000
		
		//$cod_barra = sprintf("%03d%05d%07d", $fami_id, $grup_id, $prod_id);
		// se trunca $fami_id, $grup_id, $prod_id por si son mas largos (resultado de carga masiva y secuencias que se agrandan)
		$cod_barra = sprintf("%03d%05d%07d", substr($fami_id, 0, 3), substr($grup_id, 0, 5), substr($prod_id, 0, 7));
		return $cod_barra;
	}
	
	function obtieneMaxCorrelativoCodBarra($prod_id) {
		// query nueva, busca ultimo codigo de barra del producto para asi ingresar el siguiente correlativo.
		// sacar los ultimos 7 numeros (corresp al correlativo)
		// 0000013
		// multiplicarlo x 1 (para sacar la parte entera)
		// Obs: Este cambio se genera a partir del requerimiento de permitir eliminar codigos de barra ya que
		// se pierde el correlativo original
		
		$sql = "select substring(ubaf_codigo, 13)::numeric as max_correlativo
				from ubicaciones_activos_fijos
				where prod_id = ".$prod_id."
				order by max_correlativo desc
				limit 1";
		
		$res = $this->query($sql);
		
		if (empty($res)) {
			$max_correlativo = 0;
		} else {
			$max_correlativo = $res[0][0]['max_correlativo'];
		}
		
		return $max_correlativo;
	}
	
	// funcion elimina activos_fijos, detalles y ubicaciones
	function deleteAll($acfi_id) {
		$info_acfi = $this->find('first', array('fields' => array('ActivoFijo.*'), 'conditions' => array('ActivoFijo.acfi_id' => $acfi_id)));
		$ceco_id = $info_acfi['ActivoFijo']['ceco_id'];
		
		// eliminamos acfi (cascada deaf)
		if (!$this->delete($acfi_id)) {
			return false;
		}
		
		$deaf_codigo = array();
		
		foreach ($info_acfi['DetalleActivoFijo'] as $row) { 
			$deaf_codigo[] = "'".$row['deaf_codigo']."'";
		}
		
		$sql = " delete 
				 from ubicaciones_activos_fijos
				 where ceco_id = ".$ceco_id."
				 and   ubaf_codigo in (".implode(", ", $deaf_codigo).")";
		$this->query($sql);
		
		return true;
	}
	
	function infoMailNuevoTraslado($acfi_id) {
		$sql = "select ceco_hij.ceco_nombre as ceco_nombre_hijo
					  ,acfi.acfi_correlativo as correlativo
					  ,ceco_pad.ceco_nombre as ceco_nombre_padre
					  ,acfi.acfi_id as acfi_id
					  ,ceco_hij.ceco_id
					  ,(select conf_valor
						from configuraciones
						where conf_id = 'site_logo') as logo
				from activos_fijos as acfi
				join centros_costos as ceco_pad on (acfi.ceco_id_padre = ceco_pad.ceco_id)
				join centros_costos as ceco_hij on (acfi.ceco_id = ceco_hij.ceco_id)
				where acfi.acfi_id = ".$acfi_id;
		$res = $this->query($sql);
		$vars = $res[0][0];
		
		// rescatamos a los responsables
		$sql = "select usua.usua_email
				from responsables as resp
				join usuarios as usua using (usua_id)
				where resp.esre_id = 1
				and   resp.ceco_id = ".$vars['ceco_id'];
		
		$res = $this->query($sql);
		$responsables = array();
		
		foreach ($res as $row) {
			$row = array_pop($row);
			$responsables[] = $row['usua_email'];
		}
		
		return array($vars, $responsables);
	}
	
	function infoMailAceptaRecepcion($acfi_id) {
		$sql = "select ceco_pad.ceco_nombre as ceco_nombre_padre
					  ,acfi_pad.acfi_correlativo as correlativo
					  ,ceco_hij.ceco_nombre as ceco_nombre_hijo
					  ,acfi_pad.acfi_id as acfi_id
					  ,acfi_pad.ceco_id
					  ,(select conf_valor
					    from configuraciones
						where conf_id = 'site_logo') as logo
				from activos_fijos as acfi_hij
				join activos_fijos as acfi_pad on (acfi_hij.acfi_id_padre = acfi_pad.acfi_id)
				join centros_costos as ceco_pad on (acfi_hij.ceco_id_padre = ceco_pad.ceco_id)
				join centros_costos as ceco_hij on (acfi_hij.ceco_id = ceco_hij.ceco_id)
				where acfi_hij.acfi_id = ".$acfi_id;
		$res = $this->query($sql);
		$vars = $res[0][0];
		
		// rescatamos a los responsables
		$sql = "select usua.usua_email
				from responsables as resp
				join usuarios as usua using (usua_id)
				where resp.esre_id = 1
				and   resp.ceco_id = ".$vars['ceco_id'];
		
		$res = $this->query($sql);
		$responsables = array();
		
		foreach ($res as $row) {
			$row = array_pop($row);
			$responsables[] = $row['usua_email'];
		}
		
		return array($vars, $responsables);
	}
	
	function infoMailRechazaRecepcion($acfi_id) {
		return $this->infoMailAceptaRecepcion($acfi_id);
	}
	
	function rechazaRecepcion($acfi_id) {
		$info = $this->read('ActivoFijo.acfi_id_padre', $acfi_id);
		$acfi_id_padre = $info['ActivoFijo']['acfi_id_padre'];
		
		$sql = "update activos_fijos set esre_id = 2 where acfi_id in (".$acfi_id.", ".$acfi_id_padre.")";
		$rs = $this->query($sql);
		
		return true;
	}
	
	function aceptaRecepcion($acfi_id) {
		$info = $this->read('ActivoFijo.acfi_id_padre', $acfi_id);
		$acfi_id_padre = $info['ActivoFijo']['acfi_id_padre'];
		
		$sql = "update activos_fijos set esre_id = 1 where acfi_id in (".$acfi_id.", ".$acfi_id_padre.")";
		$rs = $this->query($sql);
		
		$sql = "select traslada_activos_fijos(".$acfi_id.")";
		$rs = $this->query($sql);
		
		return true;
	}
	
	function itemsTransito() {
		$sql = "select prod.prod_id
		              ,deaf.deaf_codigo
					  ,prod.prod_nombre
					  ,tibi.tibi_nombre
					  ,prop.prop_nombre
					  ,situ.situ_nombre
					  ,marc.marc_nombre
					  ,colo.colo_nombre
					  ,deaf.deaf_fecha_garantia
					  ,deaf.deaf_precio
					  ,deaf.deaf_depreciable
					  ,deaf.deaf_vida_util
					  ,tmov.tmov_descripcion
					  ,ceco_pad.ceco_nombre as ceco_padre
					  ,ceco.ceco_nombre 
					  ,ceco_hij.ceco_nombre as ceco_hijo
				from detalle_activos_fijos as deaf
				join activos_fijos as acfi using (acfi_id)
				join productos as prod using (prod_id)
				join tipos_bienes as tibi using (tibi_id)
				join propiedades as prop using (prop_id)
				join situaciones as situ using (situ_id)
				join marcas as marc using (marc_id)
				join colores as colo using (colo_id)
				join tipo_movimientos as tmov using (tmov_id)
				join centros_costos as ceco using (ceco_id)
				left join centros_costos as ceco_pad on (acfi.ceco_id_padre = ceco_pad.ceco_id)
				left join centros_costos as ceco_hij on (acfi.ceco_id_hijo = ceco_hij.ceco_id)
				where acfi.esre_id = 2
				order by deaf.deaf_id desc";
				
		$rs = $this->query($sql);
		
		return $rs;
	}
	
	function generaImgCodBarra($info, $conf) {
		$logo_sist = base64_decode(ClassRegistry::init('Configuracion')->obtieneLogo());
		
		try {
			$im_logo = new Imagick();
			$im_logo->readImageBlob($logo_sist);
			$im_logo->thumbnailImage(60, 60, true);
						
		} catch (ImagickException $e) {
			throw new Exception($e->getMessage());
		}
		
		$path = Configure::read('codBarraPath');		
	
		if ($path == "") {
			throw new Exception("No se encuentra variable de configuración codBarraPath. Por favor contáctese con el administrador del sistema.");
		} else {
		
			if (!file_exists($path)) {
				if (!mkdir($path)) {
					throw new Exception("No se pudo crear el directorio ".$path.". Por favor contáctese con el administrador del sistema.");
				}
			}
			
			$imgs = array();
			foreach ($info as $row) {
				$prod_nombre = utf8_decode($row['Producto']['prod_nombre']);
				$ceco_nombre = utf8_decode($row['CentroCosto']['ceco_nombre']);
				$acfi_fecha = date("d-m-Y H:i:s", strtotime($row['UbicacionActivoFijo']['ubaf_fecha']));
				$deaf_codigo = $row['UbicacionActivoFijo']['ubaf_codigo'];
				$ubaf_serie = utf8_decode($row['UbicacionActivoFijo']['ubaf_serie']);
				$propiedad = utf8_decode($row['Propiedad']['prop_nombre']);
				
				// Cambiamos ancho en caso de tener titulo
				if (isset($conf['barcode_titulo_nombre']) && $conf['barcode_titulo_nombre'] != '0') {						
					$im = imagecreatetruecolor($conf['barcode_width'], 145);
				} else {
					$im = imagecreatetruecolor($conf['barcode_width'], 130);		
				}
				
				$white = imagecolorallocate($im, 255, 255, 255);
				$black = imagecolorallocate($im, 0, 0, 0);
				
				$largoNombre = 4;
				$largoUbicacion = 115;
				$largoCodbarra = 30;
				$largoLogo = 30;
				$anchoLogo = 33;
				$widthBarcode = 75;
				$widthCodigo = 88;
				$heightCodigo = 97;				
		
				// Cambiamos ancho en caso de tener titulo
				if (isset($conf['barcode_titulo_nombre']) && $conf['barcode_titulo_nombre'] != '0') {
					imagefilledrectangle($im, 0, 0, 400, 145, $white);
					// Cambiamos largo de texto al agregar titulo
					$largoNombre = 22;
					$largoUbicacion = 128;
					$largoCodbarra = 42;
					$largoLogo = 45;		
				} else {
					imagefilledrectangle($im, 0, 0, 400, 130, $white);
				}
				
				if ($conf['barcode_logo'] == 1) {
					$widthCodigo = 120;				
					$logo = imagecreatefromstring($im_logo);
					//imagecopy($im, $logo, 15, 12, 0, 0, imagesx($logo), imagesy($logo));
					imagecopy($im, $logo, 10, $largoLogo, 0, 0, imagesx($logo), imagesy($logo));
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
						
						$largoCodbarra = 37;
						$anchoLogo = 50;
						$barcode_type = Image_Barcode2::BARCODE_INT25;						
						$widthBarcode = 85;						
						// Se agrega codigo de barra ya que este tipo de codificación no lo muestra
						imagestring ($im, 4, $widthCodigo, $heightCodigo, $deaf_codigo, $black);
					} elseif ($conf['barcode_type'] == "postnet") {
						$barcode_type = Image_Barcode2::BARCODE_POSTNET;
					} elseif ($conf['barcode_type'] == "upca") {
						$barcode_type = Image_Barcode2::BARCODE_UPCA;
					}
					
					$cod_barra = $barcode->draw($deaf_codigo, $barcode_type, Image_Barcode2::IMAGE_PNG, false);
				} catch (Image_Barcode2_Exception $e) {
					throw new Exception($e->getMessage());
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
					$ancho_texto = ($largo * 9) / 2;
					$center = 166;
					$xpos = $center - $ancho_texto;
					$largoCodbarra = 46;
					
					// Titulo
					imagestring ($im, 5, $xpos, 4, $text, $black);
				}
				
				//imagecopyresized($im, $cod_barra, 55, 30, 0, 0, 347, 70, imagesx($cod_barra), imagesy($cod_barra));
				if ($conf['barcode_logo'] == 0) {
					imagecopy ($im, $cod_barra, $anchoLogo, $largoCodbarra, 0, 0, imagesx($cod_barra), imagesy($cod_barra));
				} else {
					imagecopy ($im, $cod_barra, $widthBarcode, $largoCodbarra, 0, 0, imagesx($cod_barra), imagesy($cod_barra));
				}
				
				if ($conf['barcode_prod'] == 1) {					
					imagestring ($im, 5, 10, $largoNombre, $prod_nombre, $black);
				}
				
				$ubicacion = "/".str_replace(" / ", "/", $row['UbicacionActivoFijo']['ubaf_ubicacion']);
				//$ubicacion = basename(dirname($ubicacion))."/".basename($ubicacion);
				if (strpos($ubicacion, "/") == 0) $ubicacion = substr($ubicacion, 1);
				$ubicacion = utf8_decode($ubicacion);
				
				// Mostramos ubicación de fin a inicio. (Reversa)
				$ubicacionInicioFin = array_reverse(explode('/', $ubicacion));
				$ubicacionReversa = implode('/', $ubicacionInicioFin);
			
				if ($conf['barcode_date'] == 1 && $conf['barcode_cc'] == 1) {
					//$str = $acfi_fecha." - ".$ceco_nombre;
					// pedido por rcarranza 13-08-2012
					$str = $acfi_fecha." - ".$ubicacionReversa;
				} elseif ($conf['barcode_date'] == 1 && $conf['barcode_cc'] == 0) {
					$str = $acfi_fecha;
				} elseif ($conf['barcode_date'] == 0 && $conf['barcode_cc'] == 1) {
					//$str = $ceco_nombre;
					// pedido por rcarranza 13-08-2012
					$str = '';
					// Se debe cambiar el ancho de la imagen a 125 en configuraciones
					imagestring ($im, 5, 10, $largoUbicacion, $ubicacionReversa, $black);	
				} elseif ($conf['barcode_date'] == 0 && $conf['barcode_cc'] == 0) {
					$str = null;
				}
				
				imagestring ($im, 2, 5, imagesy($im)-15, $str, $black);
				
				if (isset($conf['barcode_serie']) && $conf['barcode_serie'] == 1) {
					//imagestring ($im, 2, 5, 4, $ubaf_serie, $black);
				}
				
				$img = "/files/cod_barra/".$deaf_codigo.".png";
				
				if (!imagepng($im, $path."/".$deaf_codigo.".png")) {
					throw new Exception("No se pudo crear la imagen ".$deaf_codigo.".png");
				}
				$imgs[] = $img;
			}
			
			return $imgs;
		}
	}	
	
	function searchAll($string, $ceco_id, $tmov_id) {
		$string = strtolower($string);
		
		$sql = "select distinct 
					acfi.acfi_correlativo 
					,acfi.acfi_fecha
					,acfi.acfi_id
				from detalle_activos_fijos as deaf
				join activos_fijos as acfi using (acfi_id)
				join productos as prod using (prod_id)
				left join proveedores as prov using (prov_id)
				where acfi.tmov_id = 1
				and acfi.esre_id = 1
				and acfi.ceco_id = ".$ceco_id."
				and (to_char(acfi.acfi_fecha, 'DD-MM-YYYY') 			    like '%".$string."%'
					or lower(prov.prov_nombre)          			        like '%".$string."%'
					or lower(prod.prod_nombre)          			        like '%".$string."%'
					or lower(deaf.deaf_serie)           			        like '%".$string."%'
					or to_char(deaf.deaf_fecha_adquisicion, 'DD-MM-YYYY')   like '%".$string."%'
					or to_char(deaf.deaf_fecha_garantia, 'DD-MM-YYYY')      like '%".$string."%'
					or lower(acfi.acfi_orden_compra)                        like '%".$string."%'
					or lower(acfi.acfi_nro_documento)                       like '%".$string."%'
				)	order by acfi.acfi_correlativo asc";
		$res = $this->query($sql);
		return $res;		
	}
	
	function maxTrazabilidad() {
		$sql = "select coalesce(max(traf_id), 0) + 1 as maximo
				from trazabilidades_activos_fijos";
		$res = $this->query($sql);
		return $res[0][0]['maximo'];
	}
}
?>
