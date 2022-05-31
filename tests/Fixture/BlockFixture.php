<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * BlockFixture
 */
class BlockFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'block';
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id_block' => 1,
                'bloqueur' => 'Lorem ipsum dolor sit amet',
                'bloque' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}
