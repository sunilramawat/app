<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SymptomsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SymptomsTable Test Case
 */
class SymptomsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SymptomsTable
     */
    public $Symptoms;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
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
        $config = TableRegistry::exists('Symptoms') ? [] : ['className' => 'App\Model\Table\SymptomsTable'];
        $this->Symptoms = TableRegistry::get('Symptoms', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Symptoms);

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
