<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class AccountRoles extends Common
{
    public string $table = 'accounts_roles';
    public int|string|null $account_id = null;
    public int|string|null $role_id = null;
    public ?string $redirect = null;

    /**
     * Show role ID for current account_id.
     *
     * @return int|string|null
     */
    public function showAccountRolesId(): int|string|null
    {
        if ($this->conn === null || empty($this->account_id)) {
            return null;
        }

        $query = "SELECT role_id FROM {$this->prx}{$this->table} WHERE account_id = :account_id ORDER BY role_id ASC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':account_id', $this->account_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (is_array($row) && isset($row['role_id'])) {
            $this->role_id = $row['role_id'];
            return $this->role_id;
        }

        return null;
    }

    /**
     * Show accounts for a role ID.
     *
     * @return PDOStatement|false
     */
    public function showRolesAccountId(): PDOStatement|false
    {
        if ($this->conn === null || empty($this->role_id)) {
            return false;
        }

        $query = "SELECT account_id FROM {$this->prx}{$this->table} WHERE role_id = :role_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':role_id', $this->role_id);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Count accounts assigned to a role ID.
     *
     * @return int
     */
    public function countRoleAccounts(): int
    {
        if ($this->conn === null || empty($this->role_id)) {
            return 0;
        }

        $query = "SELECT COUNT(*) FROM {$this->prx}{$this->table} WHERE role_id = :role_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':role_id', $this->role_id);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }
}