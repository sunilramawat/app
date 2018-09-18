<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SubCategory Entity.
 *
 * @property int $sc_id
 * @property \App\Model\Entity\Sc $sc
 * @property string $sc_name
 * @property int $sc_c_id
 * @property \App\Model\Entity\Category $category
 * @property string $sc_description
 */
class SubCategory extends Entity
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
        'sc_id' => false,
    ];
}
