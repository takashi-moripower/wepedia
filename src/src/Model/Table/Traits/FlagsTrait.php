<?php

namespace App\Model\Table\Traits;

use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

trait FlagsTrait {

    public function findFlags(Query $query, array $options) {
        if (empty($options['flags'])) {
            return $query;
        }

        switch ($options['flags']) {
            case 'unpublished':
                $query = $query->where(['deleted' => 0, 'published' => 0]);
                break;

            case 'deleted':
                $query = $query->where(['deleted' => 1]);
                break;

            case 'normal':
            default:
                $query = $query->where(['deleted' => 0, 'published' => 1]);
                break;
        }

        return $query;
    }

 
    
    /**
     * 既読フラグの有無でフィルタリング
     * @param Query $query
     * @param array $options
     * @return Query
     */
    public function findRead(Query $query, array $options) {
        $table_link_name = $this->_alias . 'Users';
        $table_link = TableRegistry::get($table_link_name);

        $unreadIds = $table_link->find()
                ->where([$table_link->aliasField('user_id') => $this->_getLoginUserId()])
                ->select(Inflector::singularize(Inflector::underscore($this->_alias)) . '_id');



        if ($options['read']) {
            $query->where([
                $this->aliasField('id') . ' NOT IN' => $unreadIds
            ]);
            return $query;
        } else {
            $query->where([
                $this->aliasField('id') . ' IN' => $unreadIds
            ]);
            return $query;
        }
    }

    public function findDate(Query $query, array $options) {

        $date = date("Y/m/d", strtotime($options['date']));
        $exp = $query->newExpr()->gt('date', $date);
        $query = $query->where($exp);

        return $query;
    }

}
