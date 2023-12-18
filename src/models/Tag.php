<?php
namespace src\models;

class Tag extends BaseModel
{

    private int $id;
    private string $name;
    private int $house_id;

    public static string $table = 'tags';

    /**
     * @var array<string, array<int, string>>
     */
    public static array $rules = ['name'=>['string'],'house_id'=>['integer']];

    /**
     * @var string[]
     */
    public static array $allowedAttributes = ['name','house_id'];


    /**
     * @param array<int|string>|null $modelData
     */
    public function __construct($modelData = null)
    {
        if ($modelData) {
            parent::createFromModelData($modelData);
        }
    }

    /**
     * @return int
     */
    public function getHouseId(): int
    {
        return $this->house_id;
    }

    /**
     * @param int $house_id
     * @return void
     */
    public function setHouseId(int $house_id): void
    {
        $this->house_id = $house_id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
