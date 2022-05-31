<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CommunityPostsFixture
 */
class CommunityPostsFixture extends TestFixture
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
                'id_community_post' => 1,
                'username_community_post' => 'Lorem ipsum dolor sit amet',
                'message_community_post' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'nb_comm' => 1,
                'created' => '2022-03-19 10:19:58',
                'modified' => '2022-03-19 10:19:58',
            ],
        ];
        parent::init();
    }
}
