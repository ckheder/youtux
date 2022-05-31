<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BlockTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BlockTable Test Case
 */
class BlockTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BlockTable
     */
    protected $Block;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Block',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Block') ? [] : ['className' => BlockTable::class];
        $this->Block = $this->getTableLocator()->get('Block', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Block);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\BlockTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
