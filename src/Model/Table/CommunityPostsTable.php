<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CommunityPosts Model
 *
 * @method \App\Model\Entity\CommunityPost newEmptyEntity()
 * @method \App\Model\Entity\CommunityPost newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\CommunityPost[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CommunityPost get($primaryKey, $options = [])
 * @method \App\Model\Entity\CommunityPost findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\CommunityPost patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CommunityPost[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\CommunityPost|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CommunityPost saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CommunityPost[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CommunityPost[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\CommunityPost[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CommunityPost[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CommunityPostsTable extends Table
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

        $this->setTable('community_posts');
        $this->setDisplayField('id_community_post');
        $this->setPrimaryKey('id_community_post');

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
            ->integer('id_community_post')
            ->allowEmptyString('id_community_post', null, 'create');

        $validator
            ->scalar('username_community_post')
            ->maxLength('username_community_post', 50)
            ->requirePresence('username_community_post', 'create')
            ->notEmptyString('username_community_post');

        $validator
            ->scalar('message_community_post')
            ->requirePresence('message_community_post', 'create')
            ->notEmptyString('message_community_post');

        $validator
            ->integer('nb_comm')
            ->notEmptyString('nb_comm');

        return $validator;
    }
}
