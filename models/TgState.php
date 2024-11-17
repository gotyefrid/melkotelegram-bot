<?php
declare(strict_types=1);

namespace tg\models;

use Gotyefrid\MelkoframeworkCore\Model;

class TgState extends Model
{
    /**
     * @var int
     */
    public $id;

    public int $chat_id;

    /**
     * @var string
     */
    public string $state = '';

    public array $attributes = [
        'id',
        'chat_id',
        'state',
    ];

    public static function tableName(): string
    {
        return 'tg_states';
    }

    public function validate(): bool
    {
        return true;
    }

    /**
     * @param string $sql
     * @param array $params
     *
     * @return TgState[]
     */
    public static function find(string $sql, array $params = []): array
    {
        return parent::find($sql, $params);
    }
}