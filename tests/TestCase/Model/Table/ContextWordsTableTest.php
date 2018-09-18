<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ContextWordsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ContextWordsTable Test Case
 */
class ContextWordsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ContextWordsTable
     */
    public $ContextWords;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.context_words',
        'app.cws',
        'app.contexts',
        'app.cs'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ContextWords') ? [] : ['className' => 'App\Model\Table\ContextWordsTable'];
        $this->ContextWords = TableRegistry::get('ContextWords', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ContextWords);

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
