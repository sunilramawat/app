<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ExtraItemsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ExtraItemsTable Test Case
 */
class ExtraItemsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ExtraItemsTable
     */
    public $ExtraItems;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.extra_items',
        'app.eis',
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
        $config = TableRegistry::exists('ExtraItems') ? [] : ['className' => 'App\Model\Table\ExtraItemsTable'];
        $this->ExtraItems = TableRegistry::get('ExtraItems', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ExtraItems);

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
