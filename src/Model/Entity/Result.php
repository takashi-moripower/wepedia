<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Result Entity
 *
 * @property int $id
 * @property int $user_id
 * @property \Cake\I18n\Time $date
 * @property int $target_new
 * @property int $target_exist
 * @property int $previous_new
 * @property int $previous_exist
 * @property int $forecast_new
 * @property int $forecast_exist
 * @property int $result_new
 * @property int $result_exist
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\User $user
 */
class Result extends Entity
{
	use \App\Model\Entity\Traits\NameToIdTrait;
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
