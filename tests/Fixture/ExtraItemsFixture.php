<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ExtraItemsFixture
 *
 */
class ExtraItemsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'ei_id' => ['type' => 'integer', 'length' => 21, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'ei_name' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'ei_mc_id' => ['type' => 'integer', 'length' => 21, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ei_u_id' => ['type' => 'integer', 'length' => 21, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ei_status' => ['type' => 'integer', 'length' => 1, 'unsigned' => false, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'ei_my_cuisines' => ['type' => 'index', 'columns' => ['ei_mc_id'], 'length' => []],
            'ei_mc_id' => ['type' => 'index', 'columns' => ['ei_mc_id'], 'length' => []],
            'ei_u_id' => ['type' => 'index', 'columns' => ['ei_u_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['ei_id'], 'length' => []],
            'extra_items_ibfk_1' => ['type' => 'foreign', 'columns' => ['ei_mc_id'], 'references' => ['my_cuisines', 'mc_id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'extra_items_ibfk_2' => ['type' => 'foreign', 'columns' => ['ei_u_id'], 'references' => ['users', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'latin1_swedish_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'ei_id' => 1,
            'ei_name' => 'Lorem ipsum dolor sit amet',
            'ei_mc_id' => 1,
            'ei_u_id' => 1,
            'ei_status' => 1
        ],
    ];
}
