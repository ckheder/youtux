<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Movie Entity
 *
 * @property int $id
 * @property string $titre
 * @property string $filename
 * @property string $description
 * @property string $auteur
 * @property string $categorie
 * @property int $nb_like
 * @property int $nb_comment
 * @property int $nb_vues
 * @property string $type
 * @property \Cake\I18n\FrozenTime $created
 */
class Movie extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'id_movie' => true,
        'titre' => true,
        'filename' => true,
        'description' => true,
        'auteur' => true,
        'categorie' => true,
        'nb_like' => true,
        'nb_comment' => true,
        'nb_vues' => true,
        'type' => true,
        'allow_comment' => true,
        'created' => true,
    ];
}
