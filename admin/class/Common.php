<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

#[\AllowDynamicProperties]
class Common
{
    public ?PDO $conn = null;
    public ?PDOStatement $stmt = null;
    public string $table = '';
    public string $where = '';
    public string|array $fields = '';
    public int|string|null $id = null;
    public string $prx = '';
    public ?string $operation = null;
    public ?string $origin = null;
    protected string $prefix = '';

    /**
     * Common constructor.
     *
     * @param PDO|null $db
     */
    public function __construct(?PDO $db = null)
    {
        $this->conn = $db;
        $this->loadPrefix();
    }

    /**
     * Load database table prefix if configured.
     */
    protected function loadPrefix(): void
    {
        $candidates = [
            __DIR__ . '/../core/prefix.php',
            __DIR__ . '/../../core/prefix.php',
            'core/prefix.php',
            '../core/prefix.php',
        ];

        foreach ($candidates as $prefixFile) {
            if (is_file($prefixFile)) {
                $prefix = '';
                require $prefixFile;
                if (!empty($prefix)) {
                    $this->prefix = (string) $prefix;
                    $this->prx = str_ends_with($this->prefix, '_') ? $this->prefix : $this->prefix . '_';
                }
                return;
            }
        }
        $this->prefix = '';
        $this->prx = '';
    }

    /**
     * Get full table name with prefix.
     *
     * @param string $baseTable
     * @return string
     */
    public function getTableName(string $baseTable): string
    {
        return $this->prx !== '' ? "{$this->prx}{$baseTable}" : $baseTable;
    }

    /**
     * Show statement error for debugging.
     *
     * @param PDOStatement|null $stmt
     */
    public function showError(?PDOStatement $stmt = null): void
    {
        if ($stmt instanceof PDOStatement) {
            echo '<pre>' . htmlspecialchars(print_r($stmt->errorInfo(), true), ENT_QUOTES, 'UTF-8') . '</pre>';
        }
    }

    ///////////// INSERT

    /**
     * Insert a new record.
     *
     * @param array<int, string> $fields
     * @return bool
     */
    public function insert(array $fields): bool
    {
        if ($this->conn === null || empty($fields)) {
            return false;
        }

        $columns = implode(', ', array_map(static fn($f) => "`" . str_replace('`', '', $f) . "`", $fields));
        $placeholders = implode(', ', array_map(static fn($f) => ":{$f}", $fields));

        $query = "INSERT INTO {$this->prx}{$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->conn->prepare($query);

        foreach ($fields as $item) {
            $val = property_exists($this, $item) ? $this->$item : null;
            $stmt->bindValue(":{$item}", $val);
        }

        return $stmt->execute();
    }

    ///////////// UPDATE

    /**
     * Update an existing record.
     *
     * @param array<int, string> $fields
     * @param string $where
     * @return bool
     */
    public function update(array $fields, string $where): bool
    {
        if ($this->conn === null || empty($fields)) {
            return false;
        }

        $setParts = [];
        foreach ($fields as $item) {
            $setParts[] = "{$item} = :{$item}";
        }

        $query = "UPDATE {$this->prx}{$this->table} SET " . implode(', ', $setParts) . " WHERE {$where} = :where_param";
        $stmt = $this->conn->prepare($query);

        foreach ($fields as $item) {
            $val = property_exists($this, $item) ? $this->$item : null;
            $stmt->bindValue(":{$item}", $val);
        }

        $whereVal = property_exists($this, $where) ? $this->$where : null;
        $stmt->bindValue(':where_param', $whereVal);

        return $stmt->execute();
    }

    ///////////// SELECT

    /**
     * Show all records with optional pagination and ordering.
     *
     * @param string $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @param string $ascDesc
     * @return PDOStatement|false
     */
    public function showAll(string $orderBy, ?int $limit = null, ?int $offset = null, string $ascDesc = 'ASC'): PDOStatement|false
    {
        if ($this->conn === null) {
            return false;
        }

        $direction = strtoupper($ascDesc) === 'DESC' ? 'DESC' : 'ASC';
        $limits = '';

        if ($limit !== null && $offset !== null) {
            $limits = ' LIMIT :limit OFFSET :offset';
        } elseif ($limit !== null) {
            $limits = ' LIMIT :limit';
        }

        $query = "SELECT * FROM {$this->prx}{$this->table} ORDER BY {$orderBy} {$direction}{$limits}";
        $stmt = $this->conn->prepare($query);

        if ($limit !== null) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        }
        if ($offset !== null && $limit !== null) {
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt;
    }

    /**
     * Show the last n records inserted in a table.
     *
     * @param string $orderBy
     * @param int $limit
     * @return PDOStatement|false
     */
    public function showAllLimitDesc(string $orderBy, int $limit): PDOStatement|false
    {
        if ($this->conn === null) {
            return false;
        }

        $query = "SELECT * FROM {$this->prx}{$this->table} ORDER BY {$orderBy} DESC LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Select records with WHERE conditions.
     *
     * @param string $orderBy
     * @param array<int, string> $where
     * @param int|null $limit
     * @param int|null $offset
     * @param string $ascDesc
     * @return PDOStatement|false
     */
    public function showAllWhere(string $orderBy, array $where, ?int $limit = null, ?int $offset = null, string $ascDesc = 'ASC'): PDOStatement|false
    {
        if ($this->conn === null) {
            return false;
        }

        $whereParts = [];
        foreach ($where as $item) {
            $whereParts[] = "{$item} = :{$item}";
        }

        $whereClause = !empty($whereParts) ? implode(' AND ', $whereParts) : '1=1';
        $direction = strtoupper($ascDesc) === 'DESC' ? 'DESC' : 'ASC';

        $limitClause = '';
        if ($limit !== null && $offset !== null) {
            $limitClause = ' LIMIT :limit OFFSET :offset';
        } elseif ($limit !== null) {
            $limitClause = ' LIMIT :limit';
        }

        $query = "SELECT * FROM {$this->prx}{$this->table} WHERE {$whereClause} ORDER BY {$orderBy} {$direction}{$limitClause}";
        $stmt = $this->conn->prepare($query);

        foreach ($where as $item) {
            $val = property_exists($this, $item) ? $this->$item : null;
            $stmt->bindValue(":{$item}", $val);
        }

        if ($limit !== null) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        }
        if ($offset !== null && $limit !== null) {
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt;
    }

    /**
     * Show fields with UNION from two tables.
     *
     * @param string $orderBy
     * @param string $table1
     * @param string $table2
     * @param array<int, string> $fields
     * @return PDOStatement|false
     */
    public function showFieldsUnion(string $orderBy, string $table1, string $table2, array $fields): PDOStatement|false
    {
        if ($this->conn === null || empty($fields)) {
            return false;
        }

        $fieldList = implode(', ', $fields);
        $query = "SELECT {$fieldList} FROM {$this->prx}{$table1} UNION SELECT {$fieldList} FROM {$this->prx}{$table2} ORDER BY {$orderBy} ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Check the existence of a single record matching an attribute.
     *
     * @param string $item
     * @return bool
     */
    public function itemExists(string $item): bool
    {
        if ($this->conn === null) {
            return false;
        }

        $query = "SELECT 1 FROM {$this->prx}{$this->table} WHERE {$item} = :{$item} LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $val = property_exists($this, $item) ? $this->$item : null;
        $stmt->bindValue(":{$item}", $val);
        $stmt->execute();

        return (bool) $stmt->fetchColumn();
    }

    /**
     * Count records matching a specific field.
     *
     * @param string $item
     * @return int
     */
    public function countItem(string $item): int
    {
        if ($this->conn === null) {
            return 0;
        }

        $query = "SELECT COUNT(*) FROM {$this->prx}{$this->table} WHERE {$item} = :{$item}";
        $stmt = $this->conn->prepare($query);
        $val = property_exists($this, $item) ? $this->$item : null;
        $stmt->bindValue(":{$item}", $val);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    /**
     * Count all records of a table.
     *
     * @return int
     */
    public function countAll(): int
    {
        if ($this->conn === null) {
            return 0;
        }

        $query = "SELECT COUNT(*) as total FROM {$this->prx}{$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return isset($result['total']) ? (int) $result['total'] : 0;
    }

    ///////////// DELETE

    /**
     * Delete record by field match.
     *
     * @param string $field
     * @return bool
     */
    public function delete(string $field): bool
    {
        if ($this->conn === null) {
            return false;
        }

        $query = "DELETE FROM {$this->prx}{$this->table} WHERE {$field} = :{$field}";
        $stmt = $this->conn->prepare($query);
        $val = property_exists($this, $field) ? $this->$field : null;
        $stmt->bindValue(":{$field}", $val);

        return $stmt->execute();
    }

    ///////////// OPERATIONS ON TABLES

    /**
     * Drop a table.
     *
     * @param string $tableToDel
     * @return bool
     */
    public function dropTable(string $tableToDel): bool
    {
        if ($this->conn === null) {
            return false;
        }

        $cleanTable = preg_replace('/[^a-zA-Z0-9_]/', '', $tableToDel);
        $query = "DROP TABLE IF EXISTS `{$cleanTable}`";
        $stmt = $this->conn->prepare($query);

        return $stmt->execute();
    }

    /**
     * Clone a table structure and data.
     *
     * @param string $origTable
     * @param string $newTable
     * @param string $primaryKey
     * @return bool
     */
    public function cloneTable(string $origTable, string $newTable, string $primaryKey): bool
    {
        if ($this->conn === null) {
            return false;
        }

        $orig = preg_replace('/[^a-zA-Z0-9_]/', '', $origTable);
        $new = preg_replace('/[^a-zA-Z0-9_]/', '', $newTable);
        $pk = preg_replace('/[^a-zA-Z0-9_]/', '', $primaryKey);

        $query = "CREATE TABLE `{$new}` AS SELECT * FROM `{$orig}`; ALTER TABLE `{$new}` ADD PRIMARY KEY (`{$pk}`);";
        $stmt = $this->conn->prepare($query);

        return $stmt->execute();
    }

    ///////////// OPERATIONS ON FILES

    /**
     * Recursive chmod.
     *
     * @param string $path
     * @param int $filemode
     * @return bool
     */
    public function chmod_R(string $path, int $filemode): bool
    {
        if (!file_exists($path)) {
            return false;
        }

        if (!is_dir($path)) {
            return @chmod($path, $filemode);
        }

        $dh = @opendir($path);
        if (!$dh) {
            return false;
        }

        while (($file = readdir($dh)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $fullpath = $path . DIRECTORY_SEPARATOR . $file;
            if (is_dir($fullpath)) {
                if (!$this->chmod_R($fullpath, $filemode)) {
                    closedir($dh);
                    return false;
                }
            } else {
                if (!@chmod($fullpath, $filemode)) {
                    closedir($dh);
                    return false;
                }
            }
        }

        closedir($dh);
        return @chmod($path, $filemode);
    }

    /**
     * Recursively copy a directory.
     *
     * @param string $source
     * @param string $destination
     * @return bool
     */
    public function copyDirectory(string $source, string $destination): bool
    {
        if (!is_dir($source)) {
            return false;
        }

        if (!is_dir($destination) && !mkdir($destination, 0755, true) && !is_dir($destination)) {
            return false;
        }

        $files = scandir($source);
        if ($files === false) {
            return false;
        }

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $src = rtrim($source, '/\\') . DIRECTORY_SEPARATOR . $file;
            $dest = rtrim($destination, '/\\') . DIRECTORY_SEPARATOR . $file;

            if (is_dir($src)) {
                $this->copyDirectory($src, $dest);
            } else {
                copy($src, $dest);
            }
        }

        return true;
    }

    /**
     * Recursively remove a directory.
     *
     * @param string $dir
     * @return bool
     */
    public function rmdir_recursive(string $dir): bool
    {
        if (!is_dir($dir)) {
            return false;
        }

        $files = scandir($dir);
        if ($files === false) {
            return false;
        }

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $target = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_dir($target)) {
                $this->rmdir_recursive($target);
            } else {
                @unlink($target);
            }
        }

        return @rmdir($dir);
    }

    ///////////// MISC

    public function commaToPoint(float|int|string $number): string
    {
        return str_replace(',', '.', (string) $number);
    }

    public function pointToComma(float|int|string $number): string
    {
        return str_replace('.', ',', (string) $number);
    }

    /**
     * Get base URL up to specified stopping directory.
     *
     * @param string $stopDir
     * @return string
     */
    public function getBaseUrlBefore(string $stopDir = 'admin'): string
    {
        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443);
        $protocol = $isHttps ? 'https://' : 'http://';

        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        $path = parse_url($uri, PHP_URL_PATH) ?? '/';
        $segments = explode('/', trim($path, '/'));

        $basePath = '';
        foreach ($segments as $segment) {
            if ($segment === $stopDir) {
                break;
            }
            $basePath .= $segment . '/';
        }

        return $protocol . $host . '/' . $basePath;
    }
}
