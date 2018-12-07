<?php

namespace App\Model\Entity\Traits;


use Cake\ORM\TableRegistry;

trait FlagsTrait {
        
        public function _getFlags() {
            if( $this->deleted ){
                return 'deleted';
            }
            
            if( !$this->published ){
                return 'unpublished';
            }
            
            return 'normal';
        }
        
        public function _setFlags($value){
            if( $value == 'normal'){
                $this->deleted = 0;
                $this->published = 1;
                return;
            }
            
            if( $value == 'deleted' ){
                $this->deleted = 1;
                $this->published = 1;
            }
            
            if( $value == 'unpublished' ){
                $this->deleted = 0;
                $this->published = 0;
            }
			return $value;
        }
}
