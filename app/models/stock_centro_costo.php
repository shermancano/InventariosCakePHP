<?php
class StockCentroCosto extends AppModel {
	var $name = 'StockCentroCosto';
	var $useTable = 'stocks_cc';
	var $primaryKey = 'stcc_id';
	
	var $belongsTo = array(
	 	 'CentroCosto' => array('className' => 'CentroCosto',
	 	 	 			   		'foreignKey' => 'ceco_id')
	 	,'Producto' => array('className' => 'Producto',
	 	 	 			   	'foreignKey' => 'prod_id')
	);
	
	var $validate = array(
		'ceco_id' => array('rule' => 'numeric', 'required' => true, 'message' => 'Debe seleccionar el centro de costo')
	   ,'prod_id' => array('rule' => 'numeric', 'required' => true, 'message' => 'Debe seleccionar el producto')
	   ,'prod_nombre' => array('rule' => '/[\w\s]+/', 'required' => true, 'message' => 'Debe ingresar el nombre del producto')
	   ,'stcc_stock_critico' => array('rule' => 'numeric', 'required' => true, 'message' => 'Debe ingresar el stock critico')
	);
}
?>