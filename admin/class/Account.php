<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class Account extends Common
{
    public string $table = 'accounts';
    public ?string $username = null;
    public ?string $email = null;
    public ?string $password = null;
    public ?string $avatar = 'default.png';
    public ?string $last_login = null;
    public ?string $token = null;
    public ?string $expDate = null;
    public ?string $auth_token = null;
    public ?string $details = null;
    public ?string $details_opt = null;

    /**
     * Get temporary password reset data by token and email.
     *
     * @return array<string, mixed>|null
     */
    public function getPswTmpData(): ?array
    {
        if ($this->conn === null) {
            return null;
        }

        $query = "SELECT * FROM {$this->prx}password_reset_temp WHERE token = :token AND email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':token', $this->token);
        $stmt->bindValue(':email', $this->email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return is_array($row) ? $row : null;
    }

    /**
     * Get temporary password reset data by email.
     *
     * @return array<string, mixed>|null
     */
    public function getPswTmpDataByEmail(): ?array
    {
        if ($this->conn === null) {
            return null;
        }

        $query = "SELECT * FROM {$this->prx}password_reset_temp WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':email', $this->email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return is_array($row) ? $row : null;
    }

    /**
     * Get password reset expiration date for account email.
     *
     * @return string|null
     */
    public function getExpDate(): ?string
    {
        if ($this->conn === null) {
            return null;
        }

        $query = "SELECT expDate FROM {$this->prx}password_reset_temp WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':email', $this->email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->expDate = is_array($row) && isset($row['expDate']) ? (string) $row['expDate'] : null;
        return $this->expDate;
    }

    /**
     * Get last 3 accounts by last_login.
     *
     * @return PDOStatement|false
     */
    public function getLastLogin(): PDOStatement|false
    {
        if ($this->conn === null) {
            return false;
        }

        $query = "SELECT * FROM {$this->prx}{$this->table} ORDER BY last_login DESC LIMIT 3";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
}