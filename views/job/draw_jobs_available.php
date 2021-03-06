<?php if (!empty($jobs)): ?>
	<table class="table table-striped table-bordered table-condensed jobtable">
		<thead>
			<tr>
				<th></th>
				<th>#</th>
				<th>Name</th>
				<th>Status</th>
				<th>Elapsed</th>
				<th>ETA</th>
				<th>Progress</th>
				<th>Queue</th>
				<th>Manage</th>
			</tr>
		</thead>
		<tbody class="joblist">
			<?php foreach ($jobs AS $row): ?>
				<?php $j = $row['Job'] ?>
				<?php $bot = $j->getBot() ?>
				<tr id="job_<?php echo $j->id ?>">
					<td><i class="icon-resize-vertical"></i></td>
					<td><?php echo $j->id ?></td>
					<td><?php echo $j->getLink() ?></td>
					<td><?php echo JobStatus::getStatusHTML($j) ?></td>
					<td class="muted"><?php echo $j->getElapsedText() ?></td>
					<td class="muted"><?php echo $j->getEstimatedText() ?></td>
					<td align="right" style="width: 300px">
						<div class="progress progress-striped <?php echo ($j->get('status') == 'working') ? 'active' : '' ?>">
						  <div class="bar" style="width: <?php echo round($j->get('progress')) ?>%;"></div>
						</div>
					</td>
					<td><?php echo $j->getQueue()->getLink() ?></td>
					<td>
						<?php if ($j->get('status') == 'available'): ?>
  						<a class="btn btn-mini" href="<?php echo $j->getUrl() ?>/bump"><i class="icon-arrow-up"></i> bump</a>
  						<a class="btn btn-mini" href="<?php echo $j->getUrl() ?>/edit"><i class="icon-cog"></i> edit</a>
						<?php endif ?>
						<?php if ($j->get('status') != 'taken' && $j->get('status') != 'qa' && $j->get('status') != 'downloading' && $j->get('status') != 'slicing' ): ?>
							<a class="btn btn-mini" href="<?php echo $j->getUrl() ?>/cancel"><i class="icon-eject"></i> cancel</a>
						<?php endif ?>
						<?php if ($j->get('status') == 'qa'): ?>
						  <a class="btn btn-mini" href="<?php echo $j->getUrl() ?>/qa"><i class="icon-check"></i> verify</a>
						<?php endif ?>
						<a class="btn btn-mini" href="/job/create/job:<?php echo $j->id ?>"><i class="icon-repeat"></i> re-run</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<b>No pending jobs.</b>
<?php endif ?>
