<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UserKeysTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UserKeysTable Test Case
 */
class UserKeysTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UserKeysTable
     */
    public $UserKeys;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.user_keys',
        'app.uks',
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
        $config = TableRegistry::exists('UserKeys') ? [] : ['className' => 'App\Model\Table\UserKeysTable'];
        $this->UserKeys = TableRegistry::get('UserKeys', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UserKeys);

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
