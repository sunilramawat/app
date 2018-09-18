<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HashtagsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HashtagsTable Test Case
 */
class HashtagsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\HashtagsTable
     */
    public $Hashtags;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.hashtags',
        'app.hs'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Hashtags') ? [] : ['className' => 'App\Model\Table\HashtagsTable'];
        $this->Hashtags = TableRegistry::get('Hashtags', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Hashtags);

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
