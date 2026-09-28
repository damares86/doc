<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

class Mc extends Common
{
    public string $table = 'mc_pages';
    public ?string $page_name = null;
    public int|string|null $no_del = 0;
    public ?string $link_to_file = null;
    public ?string $layout = null;
    public int|string|null $header = null;
    public ?string $header_media = null;
    public int|string|null $use_page_name = 0;
    public int|string|null $use_name = 0;
    public int|string|null $use_desc = 0;
    public int|string|null $counter = null;
    public ?string $color = null;
    public ?string $quote = null;
    public ?string $author = null;
    public ?string $title = null;
    public ?string $content = null;
    public int|string|null $page_id = null;
    public int|string|null $popup_cat_id = null;
    public ?string $category = null;
    public ?string $name = null;
    public ?string $value = null;
    public ?string $label = null;
    public ?string $email = null;
    public ?string $gallery_name = null;
    public ?string $filename = null;
    public ?string $path = null;
    public ?string $origin = null;
    public ?string $inputFileName = null;
    public ?string $operation = null;

    /**
     * Upload file with security checks.
     *
     * @return bool
     */
    public function uploadFile(): bool
    {
        if (empty($this->filename) || empty($this->path) || empty($this->inputFileName)) {
            return false;
        }

        $targetDirectory = rtrim($this->path, '/\\') . DIRECTORY_SEPARATOR;
        $targetFile = $targetDirectory . basename($this->filename);
        $fileType = strtolower((string) pathinfo($targetFile, PATHINFO_EXTENSION));

        $allowedFileTypes = ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'doc', 'docx', 'zip', 'mp3', 'svg', 'webp'];
        if (!in_array($fileType, $allowedFileTypes, true)) {
            $origin = !empty($this->origin) ? $this->origin : 'allPages';
            header('Location: ../index.php?p=' . urlencode($origin) . '&err=formatErr');
            exit;
        }

        if (file_exists($targetFile)) {
            @rename($targetFile, $targetFile . '_old');
        }

        if (!is_dir($targetDirectory)) {
            @mkdir($targetDirectory, 0755, true);
        }

        if (is_uploaded_file($this->inputFileName) && move_uploaded_file($this->inputFileName, $targetFile)) {
            @chmod($targetFile, 0644);
            return true;
        }

        return false;
    }
}
