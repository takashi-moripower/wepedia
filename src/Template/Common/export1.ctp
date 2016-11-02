<?php

$fp = fopen('php://temp/maxmemory:' . (5 * 1024 * 1024), 'a'); {
	$columns2 = $columns;
	$columns2[0] = "#" . $columns2[0];
	fputcsv($fp, $columns2);
}
foreach ($data as $entity) {

	if (method_exists($entity, 'getCsvLine')) {
		$line = $entity->getCsvLine();
	} else {
		$line = [];
		foreach ($columns as $key) {
			$line[] = $entity[$key];
		}
	}

	fputcsv($fp, $line);
}

rewind($fp);
$csv0 = stream_get_contents($fp);
$csv = mb_convert_encoding($csv0, 'SJIS-win', 'utf8');

echo $csv;
