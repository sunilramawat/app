<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Review Entity.
 *
 * @property int $r_id
 * @property \App\Model\Entity\R $r
 * @property int $r_by
 * @property int $r_to
 * @property string $r_ratting
 * @property int $r_mc_id
 * @property \App\Model\Entity\MyCuisine $my_cuisine
 * @property string $r_comment
 * @property \Cake\I18n\Time $added_date
 */
class Review extends Entity
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
        'r_id' => false,
    ];
}
