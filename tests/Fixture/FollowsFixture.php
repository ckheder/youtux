<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FollowsFixture
 */
class FollowsFixture extends TestFixture
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
                'id_follow' => 1,
                'follower' => 'Lorem ipsum dolor sit amet',
                'following' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}
