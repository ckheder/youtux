<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateUsers extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $users = $this->table('users');
        $users->addColumn('username', 'string', ['limit' => 50])
              ->addColumn('password', 'string', ['null' => true])
              ->addColumn('email', 'string')
              ->addColumn('description', 'text', ['null' => true])
              ->addColumn('pays', 'text', ['null' => true])
              ->addColumn('created', 'datetime')
              ->addIndex('username', ['unique' => true])
              ->addIndex('email', ['unique' => true])
              ->create();

              if ($this->isMigratingUp()) {
                $users->insert([
                    [ 'username'    => 'christophe_kheder',
                    'password'    => '$2y$10$oSirKD4Hk4.YN.ALh6vAhOigNBOdcyNYarMS3Bi8NbTyBWgk2jTka',
                    'email'    => 'christophekheder@gmail.com',
                    'description'    => 'Développeur de Youtux',
                    'pays'    => 'France',
                    'created'    => '2022-02-02 17:02:02'
                    ]])
                      ->save();
            }
    }
}