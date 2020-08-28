<?php


namespace app\infrastructure\services\graph\dto;


use yii\base\Model;

class GraphDTO extends Model
{
    private $name;
    public function __construct(string $name, $config = [])
    {
        parent::__construct($config);
        $this->name = $name;
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 80]
        ];
    }
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}