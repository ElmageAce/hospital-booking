<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @param string $route
 * @param int|null $index
 * @return array|string
 */
function routeNameToArray(string $route, int $index = null)
{
    $array = explode('.', $route);

    if(!$index) return $array;

    return $array[$index] ?? null;
}

/**
 * @param string $dir
 * @param UploadedFile $uploadedFile
 * @return false|string
 */
function storeFile(string $dir, UploadedFile $uploadedFile)
{
    return Storage::putFile($dir, $uploadedFile);
}

/**
 * @param UploadedFile $uploadedFile
 * @return false|string
 */
function storeAvatar(UploadedFile $uploadedFile)
{
    return storeFile('photos/avatars', $uploadedFile);
}

/**
 * @param string $link
 * @return string
 */
function storageLink(string $link): string
{
    return Storage::url($link);
}

/**
 * @return \Illuminate\Contracts\Auth\Authenticatable|null
 */
function user()
{
    return auth()->user();
}
