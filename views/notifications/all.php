<?php echo Controller::byName('notifications')->renderView('draw', array('notifications' => $notifications)) ?>
<?php if (count($notifications) == 0): ?>
	<h1>No notifications</h1>
<?php endif ?>