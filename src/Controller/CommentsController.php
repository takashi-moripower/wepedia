<?php

namespace App\Controller;

use App\Controller\AppController;

/**
 * Comments Controller
 *
 * @property \App\Model\Table\CommentsTable $Comments
 */
class CommentsController extends AppController {

	public function add($sale_id, $parent_id = NULL) {
		
		if( $parent_id == 'root'){
			$parent_id = NULL;
		}
		
		$comment = $this->Comments->newEntity([
			'sale_id' => $sale_id,
			'parent_id' => $parent_id,
			'user_id' => $this->getLoginUser()['id']
		]);

		return $this->_edit($comment);
	}
	
	public function edit( $comment_id ){
		$comment = $this->Comments->get($comment_id);
		return $this->_edit( $comment );
	}

	protected function _edit($comment) {
		if ($this->request->is(['post', 'patch', 'put'])) {
			$this->Comments->patchEntity($comment, $this->request->data);
			$result = $this->Comments->save($comment);
			if ($result) {
				$this->Flash->success('コメントデータは正常に保存されました');
			} else {
				$this->Flash->success('コメントデータの保存に失敗しました');
			}
		}


		return $this->redirect(['controller' => 'sales', 'action' => 'view', $comment->sale_id]);
	}

}
