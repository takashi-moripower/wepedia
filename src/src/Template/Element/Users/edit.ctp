<div class="panel panel-default edit-users">
	<div>
		<table class="table table-striped table-edit table-edit-users">
			<tbody>
				<tr>
					<th class="col-xs-4">名前</th>
					<td class="col-xs-20">
						<?= $this->Form->text('name') ?>
					</td>
				</tr>
				<tr>
					<th class="col-xs-4">顔画像</th>
					<td class="col-xs-20">
						<div class="face"><?= $this->Element('face', ['id' => $user->id]) ?></div>
						<?= $this->Form->file('face'); ?>
					</td>
				</tr>
				<tr>
					<th class="col-xs-4">所属</th>
					<td class="col-xs-20">
						<?= $this->Form->text('section') ?>
					</td>
				</tr>
				<tr>
					<th class="col-xs-4">役職</th>
					<td class="col-xs-20">
						<?= $this->Form->text('position') ?>
					</td>
				</tr>
				<tr>
					<th class="col-xs-4">TEL</th>
					<td class="col-xs-20">
						<?= $this->Form->text('tel') ?>
					</td>
				</tr>
				<tr>
					<th class="col-xs-4">E-mail</th>
					<td class="col-xs-20">
						<?= $this->Form->text('email') ?>
					</td>
				</tr>
				<tr>
					<th class="col-xs-4">password</th>
					<td class="col-xs-20">
						<?= $this->Form->パスワード('password', ['default' => '']) ?>
					</td>
				</tr>
				<tr>
					<th class="col-xs-4">パスワード(確認）</th>
					<td class="col-xs-20">
						<?= $this->Form->password('password2', ['default' => '']) ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<?php $this->append('script') ?>
<script>
	$(function(){
		if(!window.FileReader){
			return;
		}
		
		face = $(".main .face img");
		face_org = face.attr("src");
		console.log("face:" + face_org);
		
		$('input[type="file"]').change(function(){
			file = $(this).prop('files')[0];
			reader = new FileReader();
			
			if(!file){
				face.attr('src',face_org);
				console.log( face_org );
				return;
			}
			
			reader.onload = function(){
				face.attr('src' , reader.result );
				console.log('loaded');
			};
			
			reader.readAsDataURL(file);
		});
	});
</script>
<?php $this->end() ?>