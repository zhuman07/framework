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
    protected static $table_name = 'users';

    protected $id;
    protected $email;
    protected $password;

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
        $this->markDirty();
    }

}