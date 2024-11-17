<?php
declare(strict_types=1);

namespace tg\models;

use Gotyefrid\MelkoframeworkCore\Model;

class User extends Model
{
    /**
     * @var int
     */
    public $id;

    public string $username;

    public int $chat_id;

    public array $attributes = [
        'id',
        'username',
        'chat_id',
    ];

    public static function tableName(): string
    {
        return 'users';
    }

    public function validate(): bool
    {
        return true;
    }

    /**
     * @param string $sql
     * @param array $params
     *
     * @return User[]
     */
    public static function find(string $sql, array $params = []): array
    {
        return parent::find($sql, $params);
    }

    /**
     * @param string $sql
     * @param array $params
     *
     * @return User|null
     */
    public static function findOne(string $sql, array $params = []): ?static
    {
        /** @var User $user */
        $user = parent::find($sql, $params)[0] ?? null;

        return  $user;
    }
}