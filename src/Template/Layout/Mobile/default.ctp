<?php

use Cake\Core\Configure;

/**
 * Default `html` block.
 */
if (!$this->fetch('html')) {
	$this->start('html');
	printf('<html lang="%s" class="no-js">', Configure::read('App.language'));
	$this->end();
}

/**
 * Default `title` block.
 */
if (!$this->fetch('title')) {
	$this->start('title');
	echo 'WE Pedia';
	$this->end();
}




/**
 * Default `flash` block.
 */
if (!$this->fetch('tb_flash')) {
	$this->start('tb_flash');
	if (isset($this->Flash))
		echo $this->Flash->render();
	$this->end();
}

/**
 * Prepend `meta` block with `author` and `favicon`.
 */
$this->prepend('meta', $this->Html->meta('author', null, ['name' => 'author', 'content' => Configure::read('App.author')]));
//$this->prepend('meta', $this->Html->meta('favicon.ico', '/favicon.ico', ['type' => 'icon']));

/**
 * Prepend `css` block with Bootstrap stylesheets and append
 * the `$html5Shim`.
 */
$html5Shim = <<<HTML
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
HTML;
$this->prepend('css', $this->Html->css(['https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css']));

$this->append('css', $html5Shim);

/**
 * Prepend `script` block with jQuery and Bootstrap scripts
 */

$this->prepend('script', $this->Html->script(['https://code.jquery.com/jquery-2.2.4.min.js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js']));
?>
<!DOCTYPE html>

<?= $this->fetch('html') ?>

<head>

	<?= $this->Html->charset() ?>

	<title><?= $this->fetch('title') ?></title>

	<?= $this->fetch('meta') ?>
	<?= $this->fetch('css') ?>

</head>
<body class="controller-<?= $this->getController() ?> action-<?= $this->getAction() ?> device-<?= $this->getDevice() ?>">
	<?php
	echo $this->Element('header');
	echo $this->fetch('tb_flash');
	echo $this->fetch('content');
	echo $this->Element('footer');
	echo $this->fetch('script');
	?>
</body>
</html>
