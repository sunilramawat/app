<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PhotoGalleriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PhotoGalleriesTable Test Case
 */
class PhotoGalleriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PhotoGalleriesTable
     */
    public $PhotoGalleries;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.photo_galleries',
        'app.pgs',
        'app.my_cuisines',
        'app.mcs',
        'app.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('PhotoGalleries') ? [] : ['className' => 'App\Model\Table\PhotoGalleriesTable'];
        $this->PhotoGalleries = TableRegistry::get('PhotoGalleries', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PhotoGalleries);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
