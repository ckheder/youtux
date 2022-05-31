<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CommunityPost Entity
 *
 * @property int $id_community_post
 * @property string $username_community_post
 * @property string $message_community_post
 * @property int $nb_comm
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class CommunityPosts extends Entity
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
        'id_community_post' => true,
        'username_community_post' => true,
        'message_community_post' => true,
        'nb_comm' => true,
        'created' => true,
        'modified' => true,
    ];
}
