<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Movies Model
 *
 * @method \App\Model\Entity\Movie newEmptyEntity()
 * @method \App\Model\Entity\Movie newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Movie[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Movie get($primaryKey, $options = [])
 * @method \App\Model\Entity\Movie findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Movie patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Movie[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Movie|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Movie saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Movie[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Movie[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Movie[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Movie[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MoviesTable extends Table
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

        $this->setTable('movies');
        $this->setDisplayField('id_movie');
        $this->setPrimaryKey('id_movie');

        $this->addBehavior('Timestamp');

        $this->hasMany('Comments');

        $this->hasMany('FavoriteMovies');
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
            ->integer('id_movie')
            ->requirePresence('id_movie', 'create');

        $validator
            ->scalar('titre')
            ->maxLength('titre', 50)
            ->requirePresence('titre', 'create')
            ->notEmptyString('titre');

        $validator
            ->scalar('filename')
            ->requirePresence('filename', 'create')
            ->notEmptyFile('filename');

        $validator
            ->scalar('description')
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

        $validator
            ->scalar('auteur')
            ->maxLength('auteur', 50)
            ->requirePresence('auteur', 'create')
            ->notEmptyString('auteur');

        $validator
            ->scalar('categorie')
            ->maxLength('categorie', 50)
            ->requirePresence('categorie', 'create')
            ->notEmptyString('categorie');

        $validator
            ->integer('nb_like')
            ->requirePresence('nb_like', 'create')
            ->notEmptyString('nb_like');

        $validator
            ->integer('nb_comment')
            ->requirePresence('nb_comment', 'create')
            ->notEmptyString('nb_comment');

        $validator
            ->integer('nb_vues')
            ->requirePresence('nb_vues', 'create')
            ->notEmptyString('nb_vues');

        $validator
            ->scalar('type')
            ->maxLength('type', 255)
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->requirePresence('allow_comment', 'create')
            ->notEmptyString('allow_comment');

        return $validator;
    }
}
