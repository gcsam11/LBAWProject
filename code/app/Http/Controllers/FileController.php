<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;

class FileController extends Controller
{
    static $default = 'default.jpg';
    static $diskName = 'Tutorial02';

    static $systemTypes = [
        'profile' => ['png', 'jpg', 'gif', 'svg', 'jpeg'],
        'post' => ['mov', 'mp4', 'gif', 'svg', 'png', 'jpg', 'jpeg'],
    ];

    private static function isValidType(String $type) {
        return array_key_exists($type, self::$systemTypes);
    }
    
    private static function defaultAsset(String $type) {
        return asset($type . '/' . self::$default);
    }
    
    private static function getFileName (String $type, int $id) {
            
        $fileName = null;
        switch($type) {
            case 'profile':
                $user = User::find($id);
                if ($user) {
                    $image_id = $user->image_id;
                    if ($image_id) {
                        $image = Image::find($image_id);
                        if ($image) {
                            $fileName = $image->filename;
                        }
                    }
                }
                break;
            case 'post':
                // other models
                break;
            }
    
        return $fileName;
    }
    
    static function get(String $type, int $userId) {
    
        // Validation: upload type
        if (!self::isValidType($type)) {
            return self::defaultAsset($type);
        }
    
        // Validation: file exists
        $fileName = self::getFileName($type, $userId);
        if ($fileName) {
            return asset($type . '/' . $fileName);
        }
    
        // Not found: returns default asset
        return self::defaultAsset($type);
    }
}
