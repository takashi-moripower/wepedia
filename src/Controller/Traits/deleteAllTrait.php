<?php

namespace App\Controller\Traits;

trait deleteAllTrait {

    public function delete_all() {

        if ($this->request->is('post')) {
            if ($this->request->data['code1'] == $this->request->data['code2']) {
                $table = $this->{$this->name};
                $result = $table->truncate();

                if ($result) {
                    $this->Flash->set('すべてのデータが削除されました');
                } else {
                    $this->Flash->set('削除に失敗しました');
                }
            } else {
                $this->Flash->set('コードが間違っています');
            }
            return $this->redirect(['action' => 'index']);
        }

        return $this->render('../Common/delete_all');
    }

}
