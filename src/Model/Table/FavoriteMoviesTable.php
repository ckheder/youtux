<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FavoriteMovies Model
 *
 * @method \App\Model\Entity\FavoriteMovie newEmptyEntity()
 * @method \App\Model\Entity\FavoriteMovie newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\FavoriteMovie[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FavoriteMovie get($primaryKey, $options = [])
 * @method \App\Model\Entity\FavoriteMovie findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\FavoriteMovie patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FavoriteMovie[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\FavoriteMovie|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FavoriteMovie saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FavoriteMovie[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FavoriteMovie[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\FavoriteMovie[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\FavoriteMovie[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class FavoriteMoviesTable extends Table
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

        $this->setTable('favorite_movies');
        $this->setDisplayField('id_favorite_movies');
        $this->setPrimaryKey('id_favorite_movies');

        $this->belongsTo('Movies')
        ->setForeignKey('favorite_movies');

        $this->addBehavior('CounterCache', [
            'Movies' => ['nb_like']]);
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
            ->integer('id_favorite_movies')
            ->allowEmptyString('id_favorite_movies', null, 'create');

        $validator
            ->scalar('username_favorite_movies')
            ->maxLength('username_favorite_movies', 50)
            ->requirePresence('username_favorite_movies', 'create')
            ->notEmptyString('username_favorite_movies');

        $validator
            ->integer('favorite_movies')
            ->requirePresence('favorite_movies', 'create')
            ->notEmptyString('favorite_movies');

        return $validator;
    }
}
