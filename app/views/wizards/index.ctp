<script language="javascript" type="text/javascript" src="/js/wizards/contenido.js"></script>

<div class="wizards form">
<!--  WIZARDS ENTRADAS / TRASLADOS -->
<div id="contenido_wizard" style="display:none;" class="contenido_wizard">
	<br />
	<table border="0">
		<tr>
			<td width="25%">
				<br />
				<div class="wizard_tipsy" title="Seleccione la acción">
					<img src="/img/wizard.jpg" alt="0"/>
				</div>
			</td>
			<td>
				<fieldset style="width:370px; height:120px">
					<legend>Paso 1</legend>
					<input type="radio" value="ent" checked="checked" name="accion" />
					<label>Entrada</label>
					<input type="radio" value="tra" name="accion" />
					<label>Traslado</label>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td></td>
			<td class="button_align">
				<input type="button" value="Siguiente" id="siguiente1" />
			</td>
		</tr>
		
	</table>
	
</div>

<div id="contenido_wizard2" style="display:none;" class="contenido_wizard">
	<br />
	<table border="0">
		<tr>
			<td width="25%">
				<br />
				<div class="wizard_tipsy2" title="Seleccione el tipo de bien">
					<img src="/img/wizard.jpg" alt="0"/>
				</div>
			</td>
			<td>
				<fieldset style="width:370px; height:120px">
					<legend>Paso 2</legend>
					<input type="radio" value="1" checked="checked" name="bien" />
					<label>Activo Fijo</label>
					<input type="radio" value="2" name="bien" />
					<label>Fungible</label>
					<input type="radio" value="3" name="bien" />
					<label>Existencia</label>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td></td>
			<td class="button_align">
				<input type="button" value="Siguiente" id="siguiente2" />
			</td>
		</tr>
		
	</table>
	
</div>
<!-- WIZARD DEPRECIACION  -->
<div id="wizard_depreciacion" style="display:none;" class="contenido_wizard">
<br />
	<table border="0">
		<tr>
			<td width="25%">
				<br />
				<div class="tipsy_depreciacion" title="Ingrese valores depreciación">
					<img src="/img/wizard.jpg" alt="0"/>
				</div>
			</td>
			<td>
				<fieldset style="width:370px; height:120px">
					<legend>Paso 1</legend>
                    <?php
    					echo $this->Form->input('ipc_activo_fijo', array('label' => 'IPC', 'onkeypress' => 'return(validchars(event,nums))', 'style' => 'width:20%', 'id' => 'ipc_activo_fijo'));
						echo $this->Form->input('depreciacion_activo_fijo', array('type' => 'date', 'label' => 'Año', 'dateFormat' => 'Y', 'minYear' => date('Y') - 6, 'maxYear' => date('Y')+88));
					?>   
				</fieldset>
			</td>
		</tr>
		<tr>
			<td></td>
			<td class="button_align">
				<input type="button" value="Siguiente" id="btn_depreciacion" />
			</td>
		</tr>		
	</table>	
</div>

<!-- LISTA DE WIZARDS -->

	<fieldset>
		<legend><?php __(utf8_encode('Wizards'));?></legend>
		<ul>
			<li><a href="javascript:;" id="contenido">Entradas/Traslado de Existencias/Activo Fijo/Fungibles</a></li> 
            <li><a href="javascript:;" id="depreciacion">Depreciación</a></li>
		</ul>
		
	</fieldset>
</div>

<?php
	include("views/sidebars/menu.php");
?>
