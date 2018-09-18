<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ExtraItem Entity.
 *
 * @property int $ei_id
 * @property \App\Model\Entity\Ei $ei
 * @property string $ei_name
 * @property int $ei_mc_id
 * @property \App\Model\Entity\MyCuisine $my_cuisine
 * @property int $ei_u_id
 * @property \App\Model\Entity\User $user
 * @property int $ei_status
 */
class ExtraItem extends Entity
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
        'ei_id' => false,
    ];
}
