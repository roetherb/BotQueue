<?php if (defined($megaerror)): ?>
	<?php echo Controller::byName('htmltemplate')->renderView('errorbar', array('message' => $megaerror)) ?>
<?php else: ?>
	<?php echo $form->render('horizontal'); ?>
<?php endif ?>