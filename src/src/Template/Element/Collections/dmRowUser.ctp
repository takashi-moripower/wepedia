<?php

$url = ['controller' => 'mypage', 'action' => 'directMail', $collection->user->id, $collection->date->format('Y-m-d')];
$label_text = $this->Element('face', ['id' => $collection->user->id]) . " " . $collection->user->name;
$label = "<div class='face32'>  ". $this->Html->link($label_text, $url, ['escape' => false]) . "</div>";

echo $this->Element('Collections/dmRow',compact(['label','collection']));