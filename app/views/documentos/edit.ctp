<div class="documentos form">
<?php echo $this->Form->create('Documento');?>
	<fieldset>
		<legend><?php __('Edit Documento'); ?></legend>
	<?php
		echo $this->Form->input('docu_id');
		echo $this->Form->input('docu_tipo');
		echo $this->Form->input('docu_nombre');
		echo $this->Form->input('docu_path');
		echo $this->Form->input('cont_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Documento.docu_id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Documento.docu_id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Documentos', true), array('action' => 'index'));?></li>
	</ul>
</div>