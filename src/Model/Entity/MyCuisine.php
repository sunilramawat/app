<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MyCuisine Entity.
 *
 * @property int $mc_id
 * @property \App\Model\Entity\Mc $mc
 * @property string $mc_name
 * @property string $mc_photo
 * @property string $timming
 * @property string $calories
 * @property string $description
 * @property string $ingredients
 * @property int $u_id
 * @property \App\Model\Entity\User $user
 * @property \Cake\I18n\Time $added_date
 */
class MyCuisine extends Entity
{

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
        'mc_id' => false,
    ];
}
