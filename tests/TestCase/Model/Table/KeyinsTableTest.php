<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\KeyinsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\KeyinsTable Test Case
 */
class KeyinsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\KeyinsTable
     */
    public $Keyins;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.keyins',
        'app.ks'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Keyins') ? [] : ['className' => 'App\Model\Table\KeyinsTable'];
        $this->Keyins = TableRegistry::get('Keyins', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Keyins);

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
