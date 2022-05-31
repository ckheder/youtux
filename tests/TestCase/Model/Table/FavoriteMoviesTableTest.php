<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FavoriteMoviesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FavoriteMoviesTable Test Case
 */
class FavoriteMoviesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FavoriteMoviesTable
     */
    protected $FavoriteMovies;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.FavoriteMovies',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('FavoriteMovies') ? [] : ['className' => FavoriteMoviesTable::class];
        $this->FavoriteMovies = $this->getTableLocator()->get('FavoriteMovies', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->FavoriteMovies);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\FavoriteMoviesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
