<?php
namespace src\models;

class Features extends BaseModel
{
    private int $id;
    private string $name;
    private string $category;
    public static string $table = 'features';
    /**
     * @var string[]
     */
    public static array $allowedAttributes = ['name', 'category'];
    /**
     * @var string[]
     */
    public static array $updatableAttributes = ['name', 'category'];
    /**
     * @var array<int|string, array<int|string>|string>
     */
    public static array $rules = ['name'=>['string'], 'category'=>['string'] ];


    /**
     * @param array<int|string> $modelData
     */
    public function __construct($modelData = null)
    {
        if ($modelData) {
            parent::createFromModelData($modelData);
        }
    }

    /**
     * Get all houses related to this feature
     *
     * @return array<string>
     */
    public function getHouses() : array
    {
        // query many-to-many relation in database
        $query = "SELECT houses_id FROM houses_has_features WHERE features_id={$this->id};";
        $result = $this->connection()->query($query);
        if (!($result instanceof \mysqli_result)) {
            return [];
        }
        // append all found house ids to array
        $houseIds = [];
        while ($row = $result->fetch_assoc()) {
            if ($row == null) {
                continue;
            }
            $houseIds[] = $row['house_id'];
        }
        return $houseIds;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
    }
}
