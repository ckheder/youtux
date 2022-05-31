<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CommunityCommentsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CommunityCommentsTable Test Case
 */
class CommunityCommentsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CommunityCommentsTable
     */
    protected $CommunityComments;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.CommunityComments',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('CommunityComments') ? [] : ['className' => CommunityCommentsTable::class];
        $this->CommunityComments = $this->getTableLocator()->get('CommunityComments', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->CommunityComments);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\CommunityCommentsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
