<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PhotoGallery Entity.
 *
 * @property int $pg_id
 * @property \App\Model\Entity\Pg $pg
 * @property int $pg_mc_id
 * @property \App\Model\Entity\MyCuisine $my_cuisine
 * @property string $ph_photo
 * @property int $pg_u_id
 * @property \App\Model\Entity\User $user
 */
class PhotoGallery extends Entity
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
        'pg_id' => false,
    ];
}
