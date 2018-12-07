<?php

use Cake\Core\Configure;

echo $this->Element('Mypage/resultSearch');
\Cake\I18n\Date::setToStringFormat('yyyy/MM');

foreach (Configure::read('result.type') as $type_id => $type_name):
	?>
	<?= $this->Element('Mypage/resultTable', ['type_name' => $type_name, 'items' => $results[$type_id]]) ?>

<?php endforeach; ?>
