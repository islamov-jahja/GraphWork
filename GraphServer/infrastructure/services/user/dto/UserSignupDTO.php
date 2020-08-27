<?php


namespace app\infrastructure\services\user\dto;


use app\domain\entities\User;
use yii\base\Model;

class UserSignupDTO extends Model
{
    public function __construct(?string $name, ?string $password, ?string $email, $config = [])
    {
        parent::__construct($config);
        $this->name = $name;
        $this->password = $password;
        $this->email = $email;
    }

    private $name;
    private $password;
    private $email;


    public function getName()
    {
        return $this->name;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function rules()
    {
        return [
            [['email', 'password', 'name'], 'required'],
            [['email'], 'email'],
            [['name', 'email'], 'string', 'max' => 80],
            [['password'], 'string', 'max' => 255],
        ];
    }

    public function getEntity(): User
    {
        return new User($this->getName(), \Yii::$app->getSecurity()->generatePasswordHash($this->password), $this->email, null);
    }
}