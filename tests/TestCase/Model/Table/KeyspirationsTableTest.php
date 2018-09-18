<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\KeyspirationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\KeyspirationsTable Test Case
 */
class KeyspirationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\KeyspirationsTable
     */
    public $Keyspirations;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.keyspirations',
        'app.ks',
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
        $config = TableRegistry::exists('Keyspirations') ? [] : ['className' => 'App\Model\Table\KeyspirationsTable'];
        $this->Keyspirations = TableRegistry::get('Keyspirations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Keyspirations);

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
