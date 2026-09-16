<?php
namespace App\Models;

use Core\Database;

abstract class Model
{
    protected string $table;
    protected array $fillable = [];
    protected array $hidden = [];

    public static function all(): array
    {
        $model = new static();
        return Database::fetchAll("SELECT * FROM {$model->table}");
    }

    public static function find($id)
    {
        $model = new static();
        return Database::fetch("SELECT * FROM {$model->table} WHERE id = ?", [$id]);
    }

    public static function where(string $column, $value)
    {
        $model = new static();
        return Database::fetch("SELECT * FROM {$model->table} WHERE {$column} = ?", [$value]);
    }

    public static function create(array $data)
    {
        $model = new static();
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        Database::query(
            "INSERT INTO {$model->table} ({$columns}) VALUES ({$placeholders})",
            array_values($data)
        );

        return Database::fetch(
            "SELECT * FROM {$model->table} WHERE id = ?",
            [Database::lastInsertId()]
        );
    }

    public function update(array $data): bool
    {
        $sets = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            $sets[] = "$key = ?";
            $values[] = $value;
        }
        
        $values[] = $this['id'];
        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE id = ?";
        
        Database::query($sql, $values);
        return true;
    }

    public function delete(): bool
    {
        Database::query("DELETE FROM {$this->table} WHERE id = ?", [$this['id']]);
        return true;
    }

    public function toArray(): array
    {
        $data = get_object_vars($this);
        foreach ($this->hidden as $field) {
            unset($data[$field]);
        }
        return $data;
    }

    public function offsetGet($offset)
    {
        return $this->$offset ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        $this->$offset = $value;
    }

    public function offsetExists($offset): bool
    {
        return property_exists($this, $offset);
    }

    public function offsetUnset($offset): void
    {
        unset($this->$offset);
    }
}
?>
