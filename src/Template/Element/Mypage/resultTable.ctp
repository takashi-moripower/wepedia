<?php
use Cake\Utility\Hash;
?>
<div class="panel panel-primary view">
	<div class="panel-heading clearfix">
		<h2 class="pull-left"><?= $type_name ?></h2>
		<div class="pull-right"><br>(単位：千円)</div>
	</div>
	<table class="table table-striped table-hover table-bordered table-index table-index-results">
		<thead>
			<tr>
				<th rowspan="2" width="10%">日付</th>
				<th colspan="5">合計</th>
				<th colspan="5">既存</th>
				<th colspan="5">新規</th>
			</tr>
			<tr>
				<th width="6%">目標</th>
				<th width="6%">前年</th>
				<th width="6%">見込</th>
				<th width="6%">実績</th>
				<th width="6%">達成率</th>
				<th width="6%">目標</th>
				<th width="6%">前年</th>
				<th width="6%">見込</th>
				<th width="6%">実績</th>
				<th width="6%">達成率</th>
				<th width="6%">目標</th>
				<th width="6%">前年</th>
				<th width="6%">見込</th>
				<th width="6%">実績</th>
				<th width="6%">達成率</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($items as $item) {
				echo $this->Element('Mypage/resultRow', ['item' => $item]);
			}
			?>
		</tbody>
		<tfoot>
			<?php
			foreach( $results_sum as $rs ){
				if( $rs->type == $item->type ){
					$rs->date = '合計';
					echo $this->Element('Mypage/resultRow' ,['item'=>$rs]);
				}
			}
			?>
		</tfoot>
	</table>
</div>
