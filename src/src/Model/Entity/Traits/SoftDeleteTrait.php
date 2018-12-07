<?php
namespace App\Model\Entity\Traits;

trait SoftDeleteTrait{
        public function softDelete(){
                $this->set('deleted',true);
        }
}