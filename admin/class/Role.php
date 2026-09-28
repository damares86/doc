<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class Role extends Common
{
    public string $table = 'roles';
    public ?string $rolename = null;
    public ?string $redirect = 'none';

    /**
     * Show role name by ID.
     *
     * @return string|null
     */
    public function showRolenameById(): ?string
    {
        if ($this->conn === null || empty($this->id)) {
            return null;
        }

        $query = "SELECT rolename FROM {$this->prx}{$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (is_array($row) && isset($row['rolename'])) {
            $this->rolename = (string) $row['rolename'];
            return $this->rolename;
        }

        return null;
    }

    /**
     * Show ID by role name.
     *
     * @return PDOStatement|false
     */
    public function showIdByRolename(): PDOStatement|false
    {
        if ($this->conn === null) {
            return false;
        }

        $query = "SELECT id FROM {$this->prx}{$this->table} WHERE rolename = :rolename";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':rolename', $this->rolename);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Check if a role exists by name.
     *
     * @return bool
     */
    public function roleExists(): bool
    {
        if ($this->conn === null || empty($this->rolename)) {
            return false;
        }

        $query = "SELECT 1 FROM {$this->prx}{$this->table} WHERE rolename = :rolename LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':rolename', $this->rolename);
        $stmt->execute();

        return (bool) $stmt->fetchColumn();
    }
}