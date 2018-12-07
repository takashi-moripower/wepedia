<div class="panel panel-primary">
	<div class="panel-heading"><h2>検索条件</h2></div>
	<div class="clearfix">
		<?php
		echo $this->Form->create();
		echo $this->Form->hidden('clear', ['value' => 1]);
		$class = empty($clear) ? 'btn-default' : 'btn-primary';
		echo $this->Form->button('全件表示', ['class' => "btn {$class} col-xs-8"]);
		echo $this->Form->end();
		?>
		<?php
		echo $this->Form->create();
		echo $this->Form->hidden('self', ['value' => 1]);
		$class = empty($self) ? 'btn-default' : 'btn-primary';
		echo $this->Form->button('自己データ', ['class' => "btn {$class} col-xs-8"]);
		echo $this->Form->end();
		?>
		<button type="button" class="btn btn-default col-xs-8" data-toggle="collapse" data-target="#searchForm" aria-expanded="true" aria-controls="searchForm">その他 <i class="fa fa-caret-down"></i></button>
	</div>
	<div class="collapse clearfix" id="searchForm">
		<?php
		echo $this->Form->create();
		
		$select_options = [''=>'営業担当'];
		foreach( $name_users as $id => $name ){
			$key = json_encode([$id]);
			
			$select_options[ $key ] = $name;
		}
		echo $this->Form->select('user_id', $select_options );
		
		echo $this->Form->text('freeword',['placeHolder'=>'フリーワード検索']);
		echo $this->Form->button('検索', ['class' => 'btn btn-default col-xs-8', 'escape' => false]);
		echo $this->Form->end();
		
		?>
	</div>
</div>