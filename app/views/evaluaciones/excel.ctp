
   <table width="100%" border="1">
      <tr>
          <td width="30%"><strong>Nombre del Proveedor:</strong></td>
          <td id="prov_nombre"><?php echo utf8_decode($info_res->info_cont->prov_nombre); ?></td>
      </tr>
      <tr>
          <td><strong>Nombre del Contrato:</strong></td>
          <td id="cont_nombre"><?php echo utf8_decode($info_res->info_cont->cont_nombre); ?></td>
      </tr>
   </table>
   
   <br />
   
   <table width="100%" border="1">
      <tr>
	     <th width="50%" colspan="2"></th>
		 <th width="12%">NOTA</th>
		 <th width="12%">PONDERACION</th>
	  </tr>
	  <?php
	  	   foreach ($info_res->info_res as $res) {
	  	      if ($res->deev_ponderacion != 0) {
	  ?>
	  <tr>
	     <th colspan="3" align="left"><?php echo utf8_decode($res->tiit_descripcion); ?></th>
		 <th colspan="1" align="left"><?php echo $res->deev_ponderacion; ?>%</th>
	  </tr>
	  <?php
	  			 $count = 1;
				 foreach ($res->Nota as $item) {
	  ?>
	             <tr>
	                <td><?php echo $count; ?></td>
	                <td><?php echo utf8_decode($item->item_descripcion); ?></td>
	                <td><?php echo $item->nota_nota; ?></td>
					<td>&nbsp;</td>
	             </tr>
	  <?php
	  				 $count++;
				 }
	  ?>
	   <tr>
	     <th colspan="2" align="left">Total</th>
		 <th colspan="1" align="left"><?php echo $res->promedio; ?></th>
		 <th colspan="1" align="left"><?php echo $res->pond; ?></th>
	  </tr>      
	  <?php
	  	      }
	  	   }
	  ?>
	  
	  <tr>
	     <th colspan="4" align="left"><br />Observaciones</th>
	  </tr>
	  <tr>
	     <td colspan="4"><?php echo utf8_decode($info_res->info_cont->eval_observaciones); ?></td>
	  </tr>
	  <tr>
	     <th colspan="3" align="left"><br />Nota Final</th>
		 <th colspan="1" align="left"><br /><?php echo $info_res->total_pond; ?></th>
	  </tr>
   </table>
