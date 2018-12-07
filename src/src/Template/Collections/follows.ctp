<div class="panel panel-primary">
	<div class="panel-heading">
		<h2>継続中案件</h2>
	</div>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th class="text-center col-xs-6">担当者名</th>
					<?php
					$ranges = current($collections)->ranges;
					foreach ($ranges as $range) {
						echo "<th class='text-center col-xs-3'>";
						echo $range['label'];
						echo "</th>";
					}
					?>
			</tr>
		</thead>
		<tbody>

			<?php foreach ($collections as $user_id => $collection): ?>
				<tr>
					<td class="face face48 text-left">
						<a href="<?= $this->Url->build(['controller'=>'mypage','action'=>'follow',$user_id])?>">
						<?= $this->Element('face', ['id' => $user_id]) ?><?= h($collection->user_name) ?>
						</a>
					</td>
					<?php foreach ($collection->ranges as $range): ?>
						<td>
							<?= $this->Html->link( $range['count'] , ['controller'=>'mypage','action'=>'follow',$user_id,$range['start'],$range['end']],['class'=>'btn btn-default text-right col-xs-24 text-right','style'=>'text-align:right']) ?>
						</td>

					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php $this->append('css') ?>
<style>
	table.table td.link.link-full a.btn{
		text-align:right;
	}
</style>
<?php $this->end() ?>