<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FavoriteMoviesFixture
 */
class FavoriteMoviesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id_favorite_movies' => 1,
                'username_favorite_movies' => 'Lorem ipsum dolor sit amet',
                'favorite_movies' => 1,
            ],
        ];
        parent::init();
    }
}
