<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace MagmaCore\Utility;

class FileUploadHandler
{

    private static array $errors = [];
    private static array $success = [];
    private static bool $ok = true;
    private static ?string $targetFile = null;
    /** */
    protected const FILE_SIZE_LIMIT = 500000;
    /** */
    protected const TEMP_FILE_NAME = 'tmp_name';
    /** */
    protected const SUPPORTED_UPLOAD_TYPES = ['files', 'images'];

    /**
     * Undocumented function
     *
     * @param string|null $fieldname
     * @param string|null $uploadType
     * @param string|null $targetDir
     * @param string|null $onSubmit
     * @return array
     */
    public static function process(?string $fieldname = null, ?string $uploadType = 'files', ?string $targetDir = null, ?string $onSubmit = null): array
    {
        self::$targetFile = $targetDir . basename(self::getFile($fieldname)['name']);
        $checkFileType = strtolower(pathinfo(self::$targetFile, PATHINFO_EXTENSION));

        if (isset($_POST[$onSubmit])) {
            $check = getimagesize(self::getFile($fieldname)[self::TEMP_FILE_NAME]);
            if ($check !== false) {
                self::$errors[] = 'File is an image -' . $check['mime'] . '.';
                self::$ok = true;
            } else {
                self::$errors[] = 'File is not an image';
                self::$ok = false;
            }
        }
        
        if (file_exists(self::$targetFile)) {
            self::$errors[] = 'File already exists';
            self::$ok = false;
        }

        /* check file size */
        if (self::getFile($fieldname)['size'] > self::FILE_SIZE_LIMIT) {
            self::$errors[] = 'Sorry, your file is too large';
            self::$ok = false;
        }

        self::checkAllowedFileType($uploadType, $checkFileType);
        
        /* check if upload is set to 0 ie fail */
        if (self::$ok === false) {
            self::$errors[] = 'Sorry, your file was not uploaded';
        } else {
            if (move_uploaded_file(self::getFile($fieldname)[self::TEMP_FILE_NAME], self::$targetFile)) {
                self::$success[] = 'The file ' . htmlspecialchars(basename(self::getFile($fieldname)['name'])) . ' has been uploaded';
            } else {
                self::$errors[] = 'Sorry, there was an error uploading your file.';
            }
        }

        return [
            self::$targetFile,
            self::$errors,
            self::$success
        ];
    }

    private static function checkAllowedFileType(string $fileType, $checkFileType)
    {
        switch ($fileType) {
            case 'images' :
                /* allow certain file type */
                if ($checkFileType != 'jpg' && $checkFileType != 'png' && $checkFileType != 'jpeg' && $checkFileType != 'gif') {
                    self::$errors[] = 'Sorry, your file type is not supported';
                    self::$ok = false;
                }
            break;
            case 'files' :
                /* allow certain file type */
                if ($checkFileType != 'yml' && $checkFileType != 'doc' && $checkFileType != 'xml' && $checkFileType != 'txt') {
                    self::$errors[] = 'Sorry, your file type is not supported';
                    self::$ok = false;
                }
            
            break;
        }        
    }

    private static function getFile(string $filename)
    {
        return isset($_FILES[$filename]) ? $_FILES[$filename] : [];
    }

    public static function getFileWithPath(): string
    {
        return self::$targetFile;
    }

    /**
     * Check whether the upload is valid or invalid
     *
     * @return boolean
     */
    public static function isValid(): bool
    {
        return self::$ok;
    }

    /**
     * Returns an array of generated errors
     *
     * @return array
     */
    public static function getErrors(): array
    {
        return self::$errors;
    }

    /**
     * Return an array of generated success
     *
     * @return array
     */
    public static function getSuccess(): array
    {
        return self::$success;
    }

}

