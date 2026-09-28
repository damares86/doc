<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class Auth extends Common
{
    public string $table = 'accounts';
    public ?string $username = null;
    public ?string $password = null;
    public ?string $email = null;
    public ?string $avatar = null;
    public ?string $last_login = null;
    public ?string $token = null;
    public ?string $expDate = null;
    public ?string $auth_token = null;

    /**
     * Check if email exists and populate properties.
     *
     * @return bool
     */
    public function emailExists(): bool
    {
        if ($this->conn === null || empty($this->email)) {
            return false;
        }

        $query = "SELECT * FROM {$this->prx}{$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $cleanEmail = filter_var(trim($this->email), FILTER_SANITIZE_EMAIL);
        $stmt->bindValue(':email', $cleanEmail);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (is_array($row)) {
            $this->id = $row['id'];
            $this->username = $row['username'] ?? null;
            $this->password = $row['password'] ?? null;
            $this->email = $row['email'] ?? null;
            $this->avatar = $row['avatar'] ?? 'default.png';
            $this->last_login = $row['last_login'] ?? null;
            return true;
        }

        return false;
    }

    /**
     * Update last_login timestamp.
     *
     * @param string $time
     * @return bool
     */
    public function updateLog(string $time): bool
    {
        if ($this->conn === null || empty($this->id)) {
            return false;
        }

        $query = "UPDATE {$this->prx}{$this->table} SET last_login = :last_login WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':last_login', $time);
        $stmt->bindValue(':id', $this->id);

        return $stmt->execute();
    }

    /**
     * Check persistent login cookie credentials.
     *
     * @return int
     */
    public function checkCookie(): int
    {
        if ($this->conn === null || empty($this->id) || empty($this->auth_token)) {
            return 0;
        }

        $query = "SELECT COUNT(*) FROM {$this->prx}{$this->table} WHERE id = :id AND auth_token = :auth_token";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $this->id);
        $stmt->bindValue(':auth_token', $this->auth_token);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }
}