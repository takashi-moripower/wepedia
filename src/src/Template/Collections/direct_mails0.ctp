<?php

use App\Defines\Defines;

$FOLLOW_NAME = Defines::SALES_FOLLOW_NAME;
?>

<div class="panel panel-primary">
	<div class="panel-heading clearfix">
		<h2 class="pull-left">DMフォロー状況</h2>
		<form class="form-inline pull-right">
			<br>
			<div class="form-group">
				<label for="exampleInputName2">件名 [発送日] </label>
				<?= $this->Form->select('date', $date_options, ['value' => $date->format('Y-m-d')]) ?>
			</div>
		</form>

	</div>
	<table class="table table-bordered">
		<thead>
			<tr>
				<td colspan='4'>
					<?= $this->Element('Mypage/dmChart', ['collection' => $collection]) ?>
				</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class='col-xs-6 text-center'>対象件数</td>
				<td class='col-xs-6 text-center'><?= $FOLLOW_NAME[Defines::SALES_FOLLOW_NO_FOLLOW] ?></td>
				<td class='col-xs-6 text-center'><?= $FOLLOW_NAME[Defines::SALES_FOLLOW_FOLLOWING] ?></td>
				<td class='col-xs-6 text-center'><?= $FOLLOW_NAME[Defines::SALES_FOLLOW_FINISHED] ?></td>
			</tr>
			<tr>
				<td class='text-center'><?= $collection->total ?></td>
				<td class='text-center'><?= $collection->no_follow ?></td>
				<td class='text-center'><?= $collection->following ?></td>
				<td class='text-center'><?= $collection->finished ?></td>
			</tr>
		</tbody>
	</table>
</div>
<div class="panel panel-primary">
	<table class="table table-bordered">
		<thead>
			<tr>
				<th class="text-center">担当者名</th>
				<th class="text-center">件数</th>
				<th class="text-center"><?= $FOLLOW_NAME[ Defines::SALES_FOLLOW_NO_FOLLOW ] ?></th>
				<th class="text-center"><?= $FOLLOW_NAME[ Defines::SALES_FOLLOW_FOLLOWING ] ?></th>
				<th class="text-center"><?= $FOLLOW_NAME[ Defines::SALES_FOLLOW_FINISHED ] ?></th>
				<th class="text-center"></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($users as $user_id) {
				echo $this->Element('Collections/dmRow', ['date' => $date, 'user_id' => $user_id]);
			}
			?>
		</tbody>
	</table>
</div>

<?php $this->append('script') ?>
<script>
	$(function () {
		url = "<?= $this->Url->build(['controller' => 'collections', 'action' => 'directMails']) ?>";
		$('select[name="date"]').on('change', function () {
			window.location.href = url + "/" + $(this).val();
		});
	});
</script>
<?php $this->end() ?>