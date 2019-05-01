<?php
/**
 * Created by PhpStorm.
 * User: zhuma
 * Date: 11.02.2019
 * Time: 1:25
 */
namespace App\Models;
use Core\Classes\DomainObject;

class User extends DomainObject
{
    protected static $table_name = 'system_users';

    public static function manyToMany(): array
    {
        return array(
            'category' => array(
                'model' => Role::class,
                'through' => 'system_users_roles',
                'foreign_key' => 'user_id',
                'foreign_key2' => 'role_id'
            )
        );
    }

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $password;

    /*
     |------------------------------------------------------------------------
     |  Getters
     |------------------------------------------------------------------------
     */

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /*
     |---------------------------------------------------------------------------------
     |  Setters
     |---------------------------------------------------------------------------------
     */

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
        $this->markDirty();
    }

}