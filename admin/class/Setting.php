<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class Setting extends Common
{
    public string $table = 'settings';
    public ?string $name = null;
    public ?string $value = null;

    /**
     * Show setting record by name.
     *
     * @return array<string, mixed>|null
     */
    public function showByName(): ?array
    {
        if ($this->conn === null || empty($this->name)) {
            return null;
        }

        $query = "SELECT * FROM {$this->prx}{$this->table} WHERE name = :name ORDER BY id ASC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':name', $this->name);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return is_array($row) ? $row : null;
    }

    /**
     * Update setting value by name.
     *
     * @return bool
     */
    public function updateValue(): bool
    {
        if ($this->conn === null || empty($this->name)) {
            return false;
        }

        $query = "UPDATE {$this->prx}{$this->table} SET value = :value WHERE name = :name";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':value', $this->value);
        $stmt->bindValue(':name', $this->name);

        return $stmt->execute();
    }
}