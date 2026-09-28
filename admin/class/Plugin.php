<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class Plugin extends Common
{
    public string $table = 'plugins';
    public ?string $pluginname = null;
    public ?string $description = null;
    public int|string|null $installed = 0;
    public int|string|null $active = 0;

    /**
     * Show plugin name by ID.
     *
     * @return string|null
     */
    public function showPluginnameById(): ?string
    {
        if ($this->conn === null || empty($this->id)) {
            return null;
        }

        $query = "SELECT pluginname FROM {$this->prx}{$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (is_array($row) && isset($row['pluginname'])) {
            return (string) $row['pluginname'];
        }

        return null;
    }

    /**
     * Check if plugin is active.
     *
     * @return int|bool
     */
    public function isActive(): int|bool
    {
        if ($this->conn === null || empty($this->pluginname)) {
            return false;
        }

        $query = "SELECT active FROM {$this->prx}{$this->table} WHERE pluginname = :pluginname LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':pluginname', $this->pluginname);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (is_array($row) && isset($row['active'])) {
            return (int) $row['active'];
        }

        return false;
    }
}