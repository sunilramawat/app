<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FeelingsFixture
 *
 */
class FeelingsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'f_id' => ['type' => 'integer', 'length' => 21, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'u_id' => ['type' => 'integer', 'length' => 21, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'feeling' => ['type' => 'integer', 'length' => 2, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'date' => ['type' => 'date', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'added_date' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'u_id' => ['type' => 'index', 'columns' => ['u_id'], 'length' => []],
            'u_id_2' => ['type' => 'index', 'columns' => ['u_id'], 'length' => []],
            'u_id_3' => ['type' => 'index', 'columns' => ['u_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['f_id'], 'length' => []],
            'feelings_ibfk_1' => ['type' => 'foreign', 'columns' => ['u_id'], 'references' => ['users', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'f_id' => 1,
            'u_id' => 1,
            'feeling' => 1,
            'date' => '2016-10-25',
            'added_date' => '2016-10-25 07:32:57'
        ],
    ];
}
