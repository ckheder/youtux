<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CommunityPostsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CommunityPostsTable Test Case
 */
class CommunityPostsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CommunityPostsTable
     */
    protected $CommunityPosts;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.CommunityPosts',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('CommunityPosts') ? [] : ['className' => CommunityPostsTable::class];
        $this->CommunityPosts = $this->getTableLocator()->get('CommunityPosts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->CommunityPosts);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\CommunityPostsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
