<?php

use App\Defines\Defines;
use Cake\Core\Configure;

$type_name = Configure::read('result.type');

echo $this->Element('Collections/resultsSearch');

foreach ($result_type as $type_id => $results):
	?>
	<?= $this->Element('Collections/resultsTable', ['items' => $results , 'type_id'=>$type_id ]) ?>
<?php endforeach ?>

	