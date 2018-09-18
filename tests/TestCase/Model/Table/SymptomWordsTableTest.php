<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SymptomWordsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SymptomWordsTable Test Case
 */
class SymptomWordsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SymptomWordsTable
     */
    public $SymptomWords;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.symptom_words',
        'app.sws',
        'app.symptoms',
        'app.s'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('SymptomWords') ? [] : ['className' => 'App\Model\Table\SymptomWordsTable'];
        $this->SymptomWords = TableRegistry::get('SymptomWords', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SymptomWords);

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
