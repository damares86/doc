<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class RolesSection extends Common
{
    public string $table = 'roles_section';
    public int|string|null $section_id = null;
    public int|string|null $role_id = null;

    /**
     * Insert role section mapping.
     *
     * @return bool
     */
    public function insertRoleSection(): bool
    {
        if ($this->conn === null) {
            return false;
        }

        $query = "INSERT INTO {$this->prx}{$this->table} SET section_id = :section_id, role_id = :role_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':section_id', $this->section_id);
        $stmt->bindValue(':role_id', $this->role_id);

        return $stmt->execute();
    }

    /**
     * Show all permissions for the given role_id.
     *
     * @return PDOStatement|false
     */
    public function showAllPermission(): PDOStatement|false
    {
        if ($this->conn === null) {
            return false;
        }

        $query = "SELECT * FROM {$this->prx}{$this->table} WHERE role_id = :role_id ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':role_id', $this->role_id);
        $stmt->execute();

        return $stmt;
    }
}
