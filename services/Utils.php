<?php

use JetBrains\PhpStorm\NoReturn;

/**
 *  This class some useful functions
 */
class Utils
{
    /**
     * Return a mixed variable extract from $_REQUEST array
     * @param string $variableName
     * @param mixed|null $defaultValue
     * @return mixed
     */
    public static function request(string $variableName, mixed $defaultValue = null) : mixed
    {
        return $_REQUEST[$variableName] ?? $defaultValue;
    }


    /**
     * Redirect to a page (action) with parameters
     * @param string $action
     * @param array $params
     * @return void
     */
    #[NoReturn]
    public static function redirect(string $action, array $params = []) : void
    {
        $url = "index.php?action=$action";
        foreach ($params as $paramName => $paramValue) {
            $url .= "&$paramName=$paramValue";
        }
        header("Location: $url");
        exit();
    }

    /**
     * Check if user is connected, if not redirect to connect page
     * @return void
     */
    public static function checkIfUserIsConnected(): void
    {
        if (!isset($_SESSION['idUser'])) {
            Utils::redirect("connect");
        }
    }

    /**
     * Check the upload image from the user
     * @param string $location
     * @return void
     */
    public static function checkUploadedPic(string $location): void
    {
        Utils::checkIfPicUploadedRight($location);
        Utils::checkPicSize($location);
    }

    /**
     * Check if the img is uploaded on the server or redirect to location with error message
     * @param string $location
     * @return void
     */
    private static function checkIfPicUploadedRight(string $location): void
    {
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            Utils::redirect($location, ['error' => 'picNotUploaded']);
        }

    }

    /**
     * Check image size and redirect to location with error message if too big
     * @param string $location
     * @return void
     */
    private static function checkPicSize(string $location): void
    {
        $maxSize = 8 * 1024 * 1024; // 8 Mo

        if ($_FILES['image']['size'] > $maxSize) {
            Utils::redirect($location, ['error' => 'picTooBig']);
        }

    }

    /**
     * Check image type and redirect to location with error message if invalid
     * @param string $location
     * @return string|null
     */
    public static function checkPicType(string $location): ?string
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['image']['tmp_name']);

        $allowed = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        if (!in_array($mime, $allowed, true)) {
            Utils::redirect($location, ['error' => 'invalidFile']);
        }

        return match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => null
        };

    }

    public static function savePicToDir(string $currentPage, string $dirPath): string{

        Utils::checkUploadedPic($currentPage);
        $extension = Utils::checkPicType($currentPage);

        $tmp = $_FILES['image']['tmp_name'];
        $name = uniqid() . "." . $extension;
        $bookNewPic = $dirPath . $name;

        move_uploaded_file($tmp, $bookNewPic);
        return $name;
    }

    public static function deleteOldPic(string $oldPic): void{
        if (!empty($oldPic) && file_exists($oldPic)) {
            unlink($oldPic);
        }
    }


}