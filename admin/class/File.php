<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class File extends Common
{
    public string $table = 'files';
    public ?string $filename = null;
    public ?string $filename_orig = null;
    public ?string $label = null;
    public ?string $inputFileName = null;
    public string $path = '../uploads/';
    public ?string $origin = null;

    /**
     * Upload physical file and record in database.
     *
     * @return bool
     */
    public function uploadFile(): bool
    {
        if (empty($this->filename) || empty($this->inputFileName)) {
            return false;
        }

        $targetDirectory = rtrim($this->path, '/\\') . DIRECTORY_SEPARATOR;
        $targetFile = $targetDirectory . $this->filename;
        $fileType = strtolower((string) pathinfo($targetFile, PATHINFO_EXTENSION));

        $allowedFileTypes = ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'doc', 'docx', 'zip', 'mp3'];
        if (!in_array($fileType, $allowedFileTypes, true)) {
            if ($this->origin !== null) {
                header('Location: ../index.php?p=' . urlencode($this->origin) . '&err=formatErr');
                exit;
            }
            return false;
        }

        if (file_exists($targetFile)) {
            @rename($targetFile, $targetFile . '_old');
        }

        if (!is_dir($targetDirectory)) {
            @mkdir($targetDirectory, 0755, true);
        }

        if (!move_uploaded_file($this->inputFileName, $targetFile)) {
            return false;
        }

        @chmod($targetFile, 0644);

        if ($this->conn === null) {
            return true;
        }

        if ($this->operation === 'add') {
            $query = "INSERT INTO {$this->prx}{$this->table} SET filename = :filename, label = :label";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':filename', $this->filename);
            $stmt->bindValue(':label', $this->label);
            return $stmt->execute();
        }

        if ($this->operation === 'edit' && !empty($this->id)) {
            $query = "UPDATE {$this->prx}{$this->table} SET filename = :filename, label = :label WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':filename', $this->filename);
            $stmt->bindValue(':label', $this->label);
            $stmt->bindValue(':id', $this->id);
            return $stmt->execute();
        }

        return true;
    }

    /**
     * Count files with matching filename.
     *
     * @return int
     */
    public function countFile(): int
    {
        if ($this->conn === null || empty($this->filename)) {
            return 0;
        }

        $query = "SELECT COUNT(*) FROM {$this->prx}{$this->table} WHERE filename = :filename";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':filename', $this->filename);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    /**
     * Show ID by original filename.
     *
     * @return int|string|null
     */
    public function showIdByFilename(): int|string|null
    {
        if ($this->conn === null || empty($this->filename_orig)) {
            return null;
        }

        $query = "SELECT id FROM {$this->prx}{$this->table} WHERE filename = :filename LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':filename', $this->filename_orig);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (is_array($row) && isset($row['id'])) {
            $this->id = $row['id'];
            return $this->id;
        }

        return null;
    }

    /**
     * Show filename by ID.
     *
     * @return string|null
     */
    public function showFilenameById(): ?string
    {
        if ($this->conn === null || empty($this->id)) {
            return null;
        }

        $query = "SELECT filename FROM {$this->prx}{$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (is_array($row) && isset($row['filename'])) {
            return (string) $row['filename'];
        }

        return null;
    }
}