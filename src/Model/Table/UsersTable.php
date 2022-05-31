<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


/**
 * Users Model
 *
 * @method \App\Model\Entity\User newEmptyEntity()
 * @method \App\Model\Entity\User newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('username')
            ->maxLength('username', 50)
            ->requirePresence('username', 'create')
            ->notEmptyString('username')
            ->add( // on veut un username entre 5 et 20 caractères
                'username',[
                            'validFormat'=>[
                                            'rule'=> array('custom','/^[a-z\d_]{5,20}$/i'),
        'message'=>'Votre nom d\'utilisateur dois faire entre 5 et 20 caracères et les caractères spéciaux ne sont pas autorisés.'
                                            ],
                            'notReserved'=>[
                                'rule'=>'notReserved',
                                'provider'=>'table',
                                'message'=>'Ce nom ne peut pas être utlisé.'
                                            ]              

                            ]);


        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->notEmptyString('password')
            //->allowEmptyString('password')
            ->add( 
                'password',[
                            'validFormat'=>[
                                            'rule'=> array('custom','/[A-Za-z0-9_~\-!@#\$%\^&\*\(\)]+$/'),
        'message'=>'Votre mot de passe doit faire entre 8 et 20 caracères et les caractères spéciaux suivants sont autorisés : ~ ! @ # $% ^ & * ().'
     ]
                            ]);

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');
            

        $validator
            ->scalar('description')
            ->requirePresence('description', 'create');

        $validator
            ->scalar('pays')
            ->requirePresence('pays', 'create');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['username']), ['errorField' => 'username','message' => 'Ce nom est déjà utilisé.']);
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email','message' => 'Cette adresse mail est déjà utilisée.']);

        return $rules;
    }

    public function notReserved($value, array $context) // tableau recensant les noms réservés , utilisé dans le menuco/menuoffline
    {
      return !in_array($value, ['login','logout','register','settings','deletemyaccount','subscriptions'], false);
    }
}
