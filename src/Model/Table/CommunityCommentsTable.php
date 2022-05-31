<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CommunityComments Model
 *
 * @method \App\Model\Entity\CommunityComment newEmptyEntity()
 * @method \App\Model\Entity\CommunityComment newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\CommunityComment[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CommunityComment get($primaryKey, $options = [])
 * @method \App\Model\Entity\CommunityComment findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\CommunityComment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CommunityComment[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\CommunityComment|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CommunityComment saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CommunityComment[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CommunityComment[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\CommunityComment[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\CommunityComment[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CommunityCommentsTable extends Table
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

        $this->setTable('community_comments');
        $this->setDisplayField('id_community_comment');
        $this->setPrimaryKey('id_community_comment');

        $this->addBehavior('Timestamp');

        $this->belongsTo('CommunityPosts')
        ->setForeignKey('idmessage_community');

        $this->addBehavior('CounterCache', [
            'CommunityPosts' => ['nb_comm']
        ]);
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
            ->integer('id_community_comment')
            ->allowEmptyString('id_community_comment', null, 'create');

        $validator
            ->scalar('username_community_comment')
            ->maxLength('username_community_comment', 50)
            ->requirePresence('username_community_comment', 'create')
            ->notEmptyString('username_community_comment');

        $validator
            ->scalar('community_comment')
            ->requirePresence('community_comment', 'create')
            ->notEmptyString('community_comment');

        $validator
            ->integer('idmessage_community')
            ->requirePresence('idmessage_community', 'create')
            ->notEmptyString('idmessage_community');

        return $validator;
    }
}
