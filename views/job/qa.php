<?php
/**
 * @package botqueue_job
 * @var string $megaerror
 * @var Job $job
 * @var Bot $bot
 * @var StorageInterface $webcam
 * @var Form $form
 * @var StorageInterface $source_file
 * @var StorageInterface $gcode_file
 */
?>
<?php if ($megaerror): ?>
	<?php echo Controller::byName('htmltemplate')->renderView('errorbar', array('message' => $megaerror)) ?>
<?php else: ?>

	<h2>Awesome! The printer <?php echo $bot->getLink() ?> has completed the <?php echo $job->getLink() ?> job.</h2>

	<div class="row">
		<div class="span6">
			<?php if ($webcam->isHydrated()): ?>
				<img src="<?php echo $webcam->getDownloadURL() ?>">
			<?php else: ?>
				<img src="/img/colorbars.gif">
			<?php endif ?>
		</div>
		<div class="span6">
			<div class="row">
				<div class="span6">
					<div class="alert alert-info">
						<p>Before this printer can start the next job, you need to do a few things:</p>
						<ol>
							<li>Remove the print from the machine.</li>
							<li>Inspect the print for errors or problems.</li>
							<li>Make sure the machine is ready for printing.</li>
						</ol>
					</div>
				</div>
				<div class="span6">
					<form method="post" autocomplete="off" action="<?php echo $job->getUrl() ?>/qa/pass">
						<div class="alert alert-success">
							<input type="hidden" name="submit" value="1">
							<button class="btn btn-large btn-success" style="float:right;" type="submit">PASS</button>
							<span>If everything is okay, click <strong>Pass</strong> and the job will be marked as completed and your bot will grab the next job in the queue (if any).</span>
						</div>
					</form>
				</div>
				<div class="span6">
					<div class="alert alert-error">
						<button class="btn btn-large btn-danger" style="float:right;" type="button"
						        onclick="$('#qa_fail').show()">FAIL
						</button>
						<span>If there are problems, click <strong>Fail</strong> and you will be presented with options to determine what to do with your bot and the job.</span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="qa_fail" style="display:none">
		<h3>Please enter some details on the print failure, and determine what should be done with the bot and job.</h3>
		<?php echo $form->render() ?>
	</div>

	<div class="row">
		<div class="span6">
			<h3>Source File: <?php echo $source_file->getLink() ?></h3>
			<?php if ($source_file->isHydrated()): ?>
				<iframe id="input_frame" frameborder="0" scrolling="no" width="100%" height="400"
				        src="<?php echo $source_file->getUrl() ?>/render"></iframe>
			<?php else: ?>
				Source file does not exist.
			<?php endif ?>
		</div>
		<div class="span6">
			<h3>GCode File: <?php echo $gcode_file->getLink() ?></h3>
			<?php if ($gcode_file->isHydrated()): ?>
				<iframe id="output_frame" frameborder="0" scrolling="no" width="100%" height="400"
				        src="<?php echo $gcode_file->getUrl() ?>/render"></iframe>
			<?php else: ?>
				GCode file does not exist yet.
			<?php endif ?>
		</div>
	</div>
<?php endif ?>