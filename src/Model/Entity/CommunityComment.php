<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CommunityComment Entity
 *
 * @property int $id_community_comment
 * @property string $username_community_comment
 * @property string $community_comment
 * @property int $idmessage_community
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class CommunityComment extends Entity
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
        'id_community_comment' => true,
        'username_community_comment' => true,
        'community_comment' => true,
        'idmessage_community' => true,
        'created' => true,
        'modified' => true,
    ];
}
