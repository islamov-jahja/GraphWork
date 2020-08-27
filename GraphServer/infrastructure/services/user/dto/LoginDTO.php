<?php


namespace app\infrastructure\services\user\dto;


use yii\base\Model;

class LoginDTO extends Model
{
    private $email;
    private $password;

    public function __construct(?string $email, ?string $password, $config = [])
    {
        parent::__construct($config);
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 80],
            [['password'], 'string', 'max' => 255],
        ];
    }
}