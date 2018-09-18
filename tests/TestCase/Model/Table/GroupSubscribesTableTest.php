<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GroupSubscribesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GroupSubscribesTable Test Case
 */
class GroupSubscribesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\GroupSubscribesTable
     */
    public $GroupSubscribes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.group_subscribes',
        'app.gs',
        'app.gs_gs',
        'app.gs_us'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('GroupSubscribes') ? [] : ['className' => 'App\Model\Table\GroupSubscribesTable'];
        $this->GroupSubscribes = TableRegistry::get('GroupSubscribes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->GroupSubscribes);

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
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
