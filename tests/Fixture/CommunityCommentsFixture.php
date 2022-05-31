<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CommunityCommentsFixture
 */
class CommunityCommentsFixture extends TestFixture
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
                'id_community_comment' => 1,
                'username_community_comment' => 'Lorem ipsum dolor sit amet',
                'community_comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'idmessage_community' => 1,
                'created' => '2022-03-28 10:30:45',
                'modified' => '2022-03-28 10:30:45',
            ],
        ];
        parent::init();
    }
}
