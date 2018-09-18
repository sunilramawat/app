<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * VotesFixture
 *
 */
class VotesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'v_id' => ['type' => 'integer', 'length' => 21, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'v_p_id' => ['type' => 'integer', 'length' => 21, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'v_u_id' => ['type' => 'integer', 'length' => 21, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'v_p_id' => ['type' => 'index', 'columns' => ['v_p_id'], 'length' => []],
            'v_u_id' => ['type' => 'index', 'columns' => ['v_u_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['v_id'], 'length' => []],
            'votes_ibfk_1' => ['type' => 'foreign', 'columns' => ['v_p_id'], 'references' => ['posts', 'p_id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'votes_ibfk_2' => ['type' => 'foreign', 'columns' => ['v_u_id'], 'references' => ['users', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
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
            'v_id' => 1,
            'v_p_id' => 1,
            'v_u_id' => 1
        ],
    ];
}
