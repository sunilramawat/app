<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PhotoGalleriesFixture
 *
 */
class PhotoGalleriesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'pg_id' => ['type' => 'integer', 'length' => 21, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'pg_mc_id' => ['type' => 'integer', 'length' => 21, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ph_photo' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'pg_u_id' => ['type' => 'integer', 'length' => 21, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'pg_mc_id' => ['type' => 'index', 'columns' => ['pg_mc_id'], 'length' => []],
            'pg_u_id' => ['type' => 'index', 'columns' => ['pg_u_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['pg_id'], 'length' => []],
            'photo_galleries_ibfk_1' => ['type' => 'foreign', 'columns' => ['pg_mc_id'], 'references' => ['my_cuisines', 'mc_id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'photo_galleries_ibfk_2' => ['type' => 'foreign', 'columns' => ['pg_u_id'], 'references' => ['users', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'pg_id' => 1,
            'pg_mc_id' => 1,
            'ph_photo' => 'Lorem ipsum dolor sit amet',
            'pg_u_id' => 1
        ],
    ];
}
