<?php


namespace App\Model;


use InvalidArgumentException;
use PDO;
use Psr\Container\ContainerInterface;

class Product
{
    const TABLE = 'products';

    private PDO $dbh;
    protected int $id;
    protected string $name;
    protected string $description;
    protected float $price;
    protected string $createdAt;

    /**
     * Product constructor.
     * @param ContainerInterface $container
     * @param int|null $id
     */
    public function __construct(ContainerInterface $container, int $id = null)
    {
        $this->dbh = $container->get(PDO::class);

        if (null !== $id) {
            $data = $this->findOne($id);
            foreach ($data as $prop => $value) {
                $this->$prop = $value;
            }
        }
    }

    public function findAll(): array
    {
        $sql = 'SELECT * FROM ' . self::TABLE;
        $smtp = $this->dbh->prepare($sql);
        $smtp->execute();
        return $smtp->fetchAll();
    }

    public function findOne($id): array
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE id=:id';
        $smtp = $this->dbh->prepare($sql);
        $smtp->bindParam(':id', $id);
        $smtp->execute();
        return $smtp->fetch();
    }

    public function save($data): bool
    {
        if (! $this->loadData($data))
            return false;

        $sql = 'INSERT INTO ' . self::TABLE . ' (name, description, price, created_at) ' .
            ' VALUES (:name, :description, :price, :created_at)';

        $smtp = $this->dbh->prepare($sql);
        $smtp->bindParam(':name', $this->name);
        $smtp->bindParam(':description', $this->description);
        $smtp->bindParam(':price', $this->price);
        $date = date('Y-m-d H:m:s', time());
        $smtp->bindParam(':created_at', $date);

        return $smtp->execute();
    }

    public function update($data): bool
    {
        if (! $this->loadData($data))
            return false;

        $sql = 'UPDATE ' . self::TABLE .
            ' SET name=:name, description=:description, price=:price' .
            ' WHERE id=:id';

        $smtp = $this->dbh->prepare($sql);
        $smtp->bindParam(':id', $this->id);
        $smtp->bindParam(':name', $this->name);
        $smtp->bindParam(':description', $this->description);
        $smtp->bindParam('price', $this->price);

        return $smtp->execute();
    }

    public function delete(int $id): int
    {
        $sql = 'DELETE FROM ' . self::TABLE . ' WHERE id=:id';
        $smtp = $this->dbh->prepare($sql);
        $smtp->bindParam(':id',  $id);

        return $smtp->execute();
    }

    private function loadData(array $data): bool
    {
        if (!$this->validate($data))
            return false;


        $this->name = htmlspecialchars($data['name']);
        $this->description = htmlspecialchars($data['description']);
        if (isset($data['price']))
            $this->price = $data['price'];

        return true;
    }

    private function validate(array $data): bool
    {
        return
            !empty($data['name']) && !empty($data['description']);
    }


}