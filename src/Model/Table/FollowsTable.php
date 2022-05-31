<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Follows Model
 *
 * @method \App\Model\Entity\Follow newEmptyEntity()
 * @method \App\Model\Entity\Follow newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Follow[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Follow get($primaryKey, $options = [])
 * @method \App\Model\Entity\Follow findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Follow patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Follow[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Follow|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Follow saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Follow[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Follow[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Follow[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Follow[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class FollowsTable extends Table
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

        $this->setTable('follows');
        $this->setDisplayField('id_follow');
        $this->setPrimaryKey('id_follow');
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
            ->integer('id_follow')
            ->allowEmptyString('id_follow', null, 'create');

        $validator
            ->scalar('follower')
            ->maxLength('follower', 50)
            ->requirePresence('follower', 'create')
            ->notEmptyString('follower');

        $validator
            ->scalar('following')
            ->maxLength('following', 50)
            ->requirePresence('following', 'create')
            ->notEmptyString('following');

        return $validator;
    }
}
