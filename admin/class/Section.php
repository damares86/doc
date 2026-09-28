<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class Section extends Common
{
    public string $table_parent = 'section_parent';
    public string $table_child = 'section_child';
    public int|string|null $parent_id = null;
    public ?string $link = null;
    public ?string $label = null;
    public ?string $icon = null;
    public int|string|null $show_menu = 1;

    /**
     * Count child sections for a parent ID.
     *
     * @param int|string $id
     * @return int
     */
    public function countChild(int|string $id): int
    {
        if ($this->conn === null) {
            return 0;
        }

        $query = "SELECT COUNT(*) FROM {$this->prx}{$this->table_child} WHERE parent_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    /**
     * Insert parent section.
     *
     * @return bool
     */
    public function insertParent(): bool
    {
        if ($this->conn === null) {
            return false;
        }

        $query = "INSERT INTO {$this->prx}{$this->table_parent} (`link`, `label`, `icon`) VALUES (:link, :label, :icon)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':link', $this->link);
        $stmt->bindValue(':label', $this->label);
        $stmt->bindValue(':icon', $this->icon);

        return $stmt->execute();
    }

    /**
     * Insert child section.
     *
     * @return bool
     */
    public function insertChild(): bool
    {
        if ($this->conn === null) {
            return false;
        }

        $query = "INSERT INTO {$this->prx}{$this->table_child} (`link`, `label`, `icon`, `parent_id`, `show_menu`) VALUES (:link, :label, :icon, :parent_id, :show_menu)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':link', $this->link);
        $stmt->bindValue(':label', $this->label);
        $stmt->bindValue(':icon', $this->icon);
        $stmt->bindValue(':parent_id', $this->parent_id);
        $stmt->bindValue(':show_menu', $this->show_menu ?? 1);

        return $stmt->execute();
    }

    /**
     * Show section by link.
     *
     * @param string $link
     * @param string $table
     * @return array<string, mixed>|null
     */
    public function showByLink(string $link, string $table): ?array
    {
        if ($this->conn === null) {
            return null;
        }

        $cleanTable = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
        $query = "SELECT * FROM {$this->prx}{$cleanTable} WHERE link = :link LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':link', $link);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return is_array($row) ? $row : null;
    }

    /**
     * Show section by ID.
     *
     * @param string $table
     * @return array<string, mixed>|null
     */
    public function showById(string $table): ?array
    {
        if ($this->conn === null || empty($this->id)) {
            return null;
        }

        $cleanTable = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
        $query = "SELECT * FROM {$this->prx}{$cleanTable} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return is_array($row) ? $row : null;
    }

    /**
     * Show all child sections for a given parent ID.
     *
     * @return PDOStatement|false
     */
    public function showAllChild(): PDOStatement|false
    {
        if ($this->conn === null) {
            return false;
        }

        $query = "SELECT * FROM {$this->prx}{$this->table_child} WHERE parent_id = :parent_id ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':parent_id', $this->parent_id);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Delete section by link.
     *
     * @param string $table
     * @return bool
     */
    public function deleteByLink(string $table): bool
    {
        if ($this->conn === null || empty($this->link)) {
            return false;
        }

        $cleanTable = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
        $query = "DELETE FROM {$this->prx}{$cleanTable} WHERE link = :link";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':link', $this->link);

        return $stmt->execute();
    }
}