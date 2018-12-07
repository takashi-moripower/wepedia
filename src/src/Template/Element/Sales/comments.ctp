<h2>コメント <small> （ チェックした項目に返信 ）</small></h2>
<div class="comments">
	
	<?= $this->Element('Sales/commentTree',['comments'=>$comments]) ?>
	<?= $this->Element('Sales/commentPost',['comment_id'=>'root']) ?>
</div>
<br>
<?php $this->append('script') ?>
<script>
$(function(){
	$('.comments input[type="checkbox"]').on('change',function(){
		$('.comments .panel').hide();
		if( $(this).prop('checked') ){
			comment_id = $(this).attr('comment_id');
			$('.comments .panel[comment_id="'+ comment_id + '"]').show();
			$('.comments input[type="checkbox"][comment_id!="'+comment_id+'"]').prop('checked',false);
		}else{
			$('.comments .panel[comment_id="root"]').show();
		}
	});
});	
</script>
	
<?php $this->end() ?>