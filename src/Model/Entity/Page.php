<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Page Entity.
 *
 * @property int $p_id
 * @property \App\Model\Entity\P $p
 * @property string $p_title
 * @property string $p_description
 * @property int $p_showing_order
 * @property int $p_status
 */
class Page extends Entity
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
        'p_id' => false,
    ];
}
