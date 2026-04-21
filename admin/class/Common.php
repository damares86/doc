<?php

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class Common
{

    public $conn;
    public $stmt;
    public $table;
    public $where;
    public $fields;
    public $id;
    public $prx;
    public $operation;
    public $origin;
    protected $prefix;


    // constructor

    public function __construct($db)
    {
        $this->conn = $db;
        // Include il file con il prefisso
        $this->loadPrefix();
    }


    // public function __construct() {
    // }

    protected function loadPrefix()
    {
        // Assicurati che il file esista
        $prefixFile = '../core/prefix.php';
        if (is_file($prefixFile)) {
            require $prefixFile;
            $this->prefix = isset($prefix) ? $prefix : '';
        } else {
            $this->prefix = ''; // Nessun prefisso se il file non esiste
        }
    }

    public function getTableName($baseTable)
    {
        // Ritorna il nome della tabella con prefisso
        return $this->prefix !== '' ? "{$this->prefix}_{$baseTable}" : $baseTable;
    }

    // error->TENERE?
    public function showError($stmt)
    {
        echo "<pre>";
        print_r($stmt->errorInfo());
        echo "</pre>";
    }


    ///////////// INSERT

    // $fields must be an array
    function insert($fields)
    {
        $i = 1;
        
        $this->fields = "";
        foreach ($fields as $item) {
            $this->fields .= "$item = :$item";
            if ($i < count($fields)) {
                $this->fields .= ", ";
            }
            $i++;
        }
        
        $query = "INSERT INTO " . $this->prx . $this->table . "
        SET " . $this->fields . "";



        $stmt = $this->conn->prepare($query);
        // echo $query . '<br>';

        foreach ($fields as $item) {
            $stmt->bindParam(":$item", $this->$item);
            // echo $item . ' -> ' . $this->$item . '<br>';
        }
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }


    ///////////// UPDATE

    // $fields must be an array
    function update($fields, $where)
    {

        $this->where = "";

        $this->fields = "";

        $i = 1;
        foreach ($fields as $item) {
            $this->fields .= "$item = :$item";
            if ($i < count($fields)) {
                $this->fields .= ", ";
            }
            $i++;
        }

        $query = "UPDATE " . $this->prx . $this->table . "
        SET " . $this->fields . " WHERE $where = :$where";

        $stmt = $this->conn->prepare($query);

        foreach ($fields as $item) {
            $stmt->bindParam(":$item", $this->$item);
        }

        $stmt->bindParam(":$where", $this->$where);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }



    ///////////// SELECT

    function showAll($orderBy, $limit = null, $offset = null, $ascDesc = "ASC")
    {

        $limits = '';

        if ($limit !== null && $offset !== null) {
            $limits = " LIMIT :limit OFFSET :offset";
        }

        $query = "SELECT *
            FROM " . $this->prx . $this->table . "
        ORDER BY " . $orderBy . " " . $ascDesc . " " . $limits . "";

        $stmt = $this->conn->prepare($query);

        if ($limit !== null && $offset !== null) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt;
    }

    // show the last n record inserted in a table
    function showAllLimitDesc($orderBy, $limit)
    {
        $query = "SELECT *
    FROM " . $this->prx . $this->table . "
    ORDER BY " . $orderBy . " DESC LIMIT " . $limit . "";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    // $where must be an array
    function showAllWhere($orderBy, $where, $limit = null, $offset = null, $ascDesc = "ASC")
    {

        $this->where = "";

        $i = 1;
        foreach ($where as $item) {
            $this->where .= "$item = :$item";
            if ($i < count($where)) {
                $this->where .= " AND ";
            }
            $i++;
        }

        $limit_query = '';
        $offset_query = '';

        if ($limit !== null) {
            $limit_query = " LIMIT :limit ";
        }
        if ($offset !== null) {
            $offset_query = " OFFSET :offset";
        }

        $query = "SELECT *
        FROM " . $this->prx . $this->table . "
        WHERE " . $this->where . "
        ORDER BY " . $orderBy . " " . $ascDesc . " " . $limit_query . $offset_query . "";

        $stmt = $this->conn->prepare($query);
        // print_r($stmt);
        foreach ($where as $item) {
            $stmt->bindParam(":$item", $this->$item);
        }

        if ($limit !== null) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        }
        if ($offset !== null) {
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt;
    }


    // fields must be an array
    function showFieldsUnion($orderBy, $table1, $table2, $fields)
    {

        $this->fields = "";
        $i = 1;
        foreach ($fields as $item) {
            $this->fields .= "$item";
            if ($i < count($fields)) {
                $this->fields .= ", ";
            }
            $i++;
        }

        $query = "SELECT " . $this->fields . "
        FROM " . $this->prx . $table1 . "
        UNION
        SELECT " . $this->fields . "
        FROM " . $this->prx . $table2 . "
        ORDER BY " . $orderBy . " ASC ";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    // check the existence of a single record
    public function itemExists($item)
    {

        $query = "SELECT *
        FROM " . $this->prx . $this->table . "
        WHERE " . $item . " = :" . $item . "
        LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":" . $item . "", $this->$item);

        // execute the query
        $stmt->execute();
        
        // get number of rows
        $num = $stmt->rowCount();

        if ($num > 0) {
            return true;
        } else {
            return false;
        }
    }

    // count how many record there are with a specific field
    public function countItem($item)
    {

        // query to check if email exists
        $query = "SELECT *
        FROM " . $this->prx . $this->table . "
        WHERE " . $item . " = :" . $item . "";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":" . $item . "", $this->$item);

        // execute the query
        $stmt->execute();

        // get number of rows
        $num = $stmt->rowCount();

        return $num;
    }

    // count all records of a table
    public function countAll()
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . "";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }


    ///////////// DELETE

    function delete($field)
    {

        $query = "DELETE FROM " . $this->prx . $this->table . " WHERE " . $field . " = :" . $field . "";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":$field", $this->$field);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }


    /////////////   OPERATIONS ON TABLES

    function dropTable($tableToDel)
    {

        $query = "DROP TABLE " . $tableToDel . "";

        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function cloneTable($origTable, $newTable, $primaryKey)
    {

        $query = "CREATE TABLE " . $newTable . " AS SELECT * FROM " . $origTable . "; ALTER TABLE " . $newTable . " ADD PRIMARY KEY (" . $primaryKey . ");";

        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }


    /////////////   OPERATIONS ON FILES

    public function chmod_R($path, $filemode)
    {
        // Usa DIRECTORY_SEPARATOR per compatibilità tra sistemi operativi
        if (!is_dir($path)) {
            return chmod($path, $filemode);
        }

        // Apri la directory
        $dh = opendir($path);
        if (!$dh) {
            return false; // Fallisce se non può aprire la directory
        }

        // Scansiona la directory
        while (($file = readdir($dh)) !== false) {
            if ($file == '.' || $file == '..') {
                continue; // Salta directory correnti e parenti
            }

            $fullpath = $path . DIRECTORY_SEPARATOR . $file;

            if (is_dir($fullpath)) {
                // Ricorsione per le directory
                if (!$this->chmod_R($fullpath, $filemode)) {
                    closedir($dh); // Chiudi in caso di errore
                    return false;
                }
            } else {
                // Imposta i permessi sul file
                if (!chmod($fullpath, $filemode)) {
                    closedir($dh); // Chiudi in caso di errore
                    return false;
                }
            }
        }

        // Chiudi la directory
        closedir($dh);

        // Imposta i permessi sulla directory stessa
        if (!chmod($path, $filemode)) {
            return false;
        }

        return true; // Successo
    }


    public function copyDirectory($source, $destination)
    {
        if (!is_dir($source)) {
            echo "Source folder not found: $source\n";
            return false;
        }

        if (!is_dir($destination)) {
            if (!mkdir($destination, 0755, true)) {
                echo "Failed to create destination: $destination\n";
                return false;
            }
        }

        $files = scandir($source);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $src = rtrim($source, '/') . '/' . $file;
                $dest = rtrim($destination, '/') . '/' . $file;

                if (is_dir($src)) {
                    $this->copyDirectory($src, $dest);
                } else {
                    if (!copy($src, $dest)) {
                        echo "Failed to copy file: $src\n";
                    }
                }
            }
        }

        return true;
    }


    public function rmdir_recursive($dir)
    {
        foreach (scandir($dir) as $file) {
            if ('.' === $file || '..' === $file) continue;
            if (is_dir($dir . '/' . $file)) $this->rmdir_recursive($dir . '/' . $file);
            else unlink($dir . '/' . $file);
        }
        rmdir($dir);
    }


    /////////////   MISC


    public function commaToPoint($number)
    {
        return str_replace(',', '.', $number);
    }

    public function pointToComma($number)
    {
        return str_replace('.', ',', $number);
    }
    public function getBaseUrlBefore($stopDir = 'admin') {
    // Determina il protocollo (http o https)
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' 
                || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

    // Host, es: boots.local
    $host = $_SERVER['HTTP_HOST'];

    // URI della richiesta, es: /damares/admin/core/tinyfilemanager.php?lang=en
    $uri = $_SERVER['REQUEST_URI'];

    // Rimuove la query string (tutto dopo il ?)
    $uri = parse_url($uri, PHP_URL_PATH);

    // Divide il path in segmenti
    $segments = explode('/', trim($uri, '/'));

    // Ricostruisce il path fino alla directory specificata (esclusa)
    $basePath = '';
    foreach ($segments as $segment) {
        if ($segment === $stopDir) break;
        $basePath .= $segment . '/';
    }

    return $protocol . $host . '/' . $basePath;
}

}
