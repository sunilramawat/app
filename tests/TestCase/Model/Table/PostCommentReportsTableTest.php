<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PostCommentReportsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PostCommentReportsTable Test Case
 */
class PostCommentReportsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PostCommentReportsTable
     */
    public $PostCommentReports;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.post_comment_reports',
        'app.pcrs',
        'app.pcs',
        'app.ps',
        'app.us'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('PostCommentReports') ? [] : ['className' => 'App\Model\Table\PostCommentReportsTable'];
        $this->PostCommentReports = TableRegistry::get('PostCommentReports', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PostCommentReports);

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
