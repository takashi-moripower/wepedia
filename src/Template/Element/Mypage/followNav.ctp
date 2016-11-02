
<div class="panel panel-primary">
	<div class="panel-heading">
		<h2>継続中案件</h2>
	</div>
	<table class="table table-bordered">
		<thead>
			<tr>
				<?php
				foreach ($collection->ranges as $range) {
					if ($range['start'] == $date_start && $range['end'] == $date_end) {
						$class = 'bg-primary';
						$bClass= 'btn btn-primary';
					} else {
						$class = '';
						$bClass= 'btn btn-default';
					}
					echo "<td class='{$class} col-xs-4 text-center link link-full'>";
					echo $this->Html->link($range['label'], [ 'controller' => 'mypage', 'action' => 'follow', $user_id, $range['start'], $range['end']], ['escape' => false, 'class' => $bClass]);
					echo "</td>";
				}
				?>
			</tr>
		</thead>
		<tbody>
			<tr>
				<?php
				foreach ($collection->ranges as $range) {

					echo "<td class='col-xs-4 text-center'>";
					echo $range ['count'];
					echo "</td>";
				}
				?>
			</tr>
		</tbody>
	</table>
</div>