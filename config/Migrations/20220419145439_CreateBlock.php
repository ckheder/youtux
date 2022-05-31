<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateBlock extends AbstractMigration
{

    public $autoId = false;
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $block = $this->table('block');

        $block->addColumn('id_block', 'integer', [
           
            'limit' => 50
        ])
                ->addPrimaryKey('id_block')
                ->addColumn('bloqueur', 'string' , ['limit' => 50])
                ->addColumn('bloque', 'string' , ['limit' => 50])
                ->addForeignKey('bloqueur', 'users', 'username', ['delete'=> 'CASCADE', 'constraint' => 'fk_bloqueur_users_username'])
                ->addForeignKey('bloque', 'users', 'username', ['delete'=> 'CASCADE', 'constraint' => 'fk_bloque_users_username'])
                ->create();
    }
}
