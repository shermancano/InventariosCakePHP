<?php
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.view.templates.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php __($site_title.' :: '); ?>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		//echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');
		echo $this->Html->css('jquery-ui.custom');
		echo $this->Html->css('jquery.treeview');
		echo $this->Html->css('tipsy');
		echo $this->Html->css('inventarios');

		echo $scripts_for_layout;
	?>
	<script type="text/javascript" src="/js/jquery.min.js"></script>
	<script type="text/javascript" src="/js/jquery-ui.custom.min.js"></script>
	<script type="text/javascript" src="/js/jquery.treeview.js"></script>
	<script type="text/javascript" src="/js/jquery.tipsy.js"></script>
</head>

<body>
	<div id="container">
		<?php
			if (!isset($site_logo)) {
		?>
			<div id="header" style='background-image: url("/img/logo_asisbo.png")'>
		<?php
			} else {
		?>
			<div id="header" style='background-image: url("data:image/png;base64,<?php echo $site_logo; ?>")'>
		<?php
			}
		?>
			<h1>
				<?php
					if (isset($usua_nombre)) {
						echo "Bienvenido:  ".$usua_nombre;
					}
					
					if (isset($ceco_nombre)) {
						echo " | <a href=\"/usuarios/selCentroCosto\">".$ceco_nombre."</a>";
						echo " | <a href=\"/usuarios/main\">Menu Principal</a>";
					}
						
				?>
			</h1>
		</div>
		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $content_for_layout; ?>

		</div>
		<div id="footer">
			<p>
				<a href="http://validator.w3.org/check?uri=referer">
					<img src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Transitional" height="31" width="88" /></a>
				<a href="http://jigsaw.w3.org/css-validator/check/referer">
				<img style="border:0;width:88px;height:31px" src="http://jigsaw.w3.org/css-validator/images/vcss" alt="¡CSS Válido!" /></a>
			</p>
         
			<?php
				/*
				echo $this->Html->link(
					$this->Html->image('cake.power.gif', array('alt'=> __('CakePHP: the rapid development php framework', true), 'border' => '0')),
					'http://www.cakephp.org/',
					array('target' => '_blank', 'escape' => false)
				);
				*/
			?>
		</div>
	</div>
	<?php //echo $this->element('sql_dump'); ?>
</body>
</html>
