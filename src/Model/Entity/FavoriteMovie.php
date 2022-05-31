<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FavoriteMovie Entity
 *
 * @property int $id_favorite_movies
 * @property string $username_favorite_movies
 * @property int $favorite_movies
 */
class FavoriteMovie extends Entity
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
        'id_favorite_movies' => true,
        'username_favorite_movies' => true,
        'favorite_movies' => true,
    ];
}
