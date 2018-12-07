<?php

use App\Defines\Defines;
use Cake\I18n\Date;

echo $this->Element('Collections/schedulesNavi', ['date' => $date]);

$WEEK_DAYS = Defines::WEEK_DAYS
?>
<div class="panel panel-primary">
	<div class="panel-body">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th class="col-xs-3 bg-primary">
						</th>
						<?php
						for ($i = 0; $i < 7; $i++):
							$date_w = new Date($date);
							$date_w->addDays($i);
							?>
							<td class="col-xs-3 text-center bg-primary">
								<?= $date_w->format('n月j日') ?>
								(<?= $WEEK_DAYS[$i] ?>)
							</td>
							<?php
						endfor;
						?>				
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($schedules as $schedule) {
						echo $this->Element('Collections/schedulesRow', ['item' => $schedule, 'date' => $date]);
					}
					?>
				</tbody>
			</table>
	</div>
</div>
