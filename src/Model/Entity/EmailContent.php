<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EmailContent Entity.
 *
 * @property int $ec_id
 * @property string $ec_unique_name
 * @property string $ec_title
 * @property string $ec_subject
 * @property string $ec_message
 * @property string $ec_keywords
 * @property string $ec_link_title
 * @property bool $ec_status
 * @property string $ec_comments
 */
class EmailContent extends Entity
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
        'ec_id' => false,
    ];
}
