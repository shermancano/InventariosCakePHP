<script language="Javascript" type="text/javascript" src="/js/usuarios/sel_centro_costo.js"></script>
<style>
ul {
	padding: 5px;
}

.a_padre {
	font-size: 14px;
	color: #EE3322;
}

.a_hijo {
    text-decoration: none;
}
</style>

<?php
	function draw($centros_costos, $primero = false) {
		if ($primero == true) {
			$str = "<ul id=\"navigation\" class=\"treeview-gray\">";
		} else {
			$str = "<ul>";
		}
		
		foreach ($centros_costos as $key => $val) {
			if (isset($val['children']) && (sizeof($val['children']) > 0)) {																	 				
				$str .= "<li><a class=\"a_padre\" href=\"/usuarios/selCentroCosto/".$val['CentroCosto']['ceco_id']."\">".$val['CentroCosto']['ceco_nombre']."</a>";
			} else {				
				$str .= "<li><a class=\"a_hijo\" href=\"/usuarios/selCentroCosto/".$val['CentroCosto']['ceco_id']."\">".$val['CentroCosto']['ceco_nombre']."</a>";
			}
			
			if (isset($val['children'])) {				
				$str .= draw($val['children']);				
			}
			$str .= "</li>\n";
			
		}
	
		$str .= "</ul>\n";
		return $str;
	}
?>
<fieldset style="margin-left: auto; margin-right: auto; width: 50%">
    <legend><?php __('Seleccionar Centro de Costo'); ?></legend>        
    <?php
        echo "   ".draw($centros_costos, true);
    ?>
</fieldset>
