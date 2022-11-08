<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;

if( ! function_exists("saveFileInStorageAndGetPath") )
{
    function saveFileInStorageAndReturnPath(UploadedFile $file, string $path, bool $public = true):string
    {
        $file_name = Str::random(15).'_'.now()->format('d-m-Y').'.'.$file->getClientOriginalExtension();
        
        Storage::putFileAs( $public ? "public/{$path}" : $path, $file, $file_name);
        
        $image_path = "{$path}/{$file_name}";

        if($public)
            $image_path = "storage/{$image_path}";

        return $image_path;
    }
}

if( ! function_exists("optimizeImage") )
{
    function optimizeImage($from, $to, $quality)
    {
        $info = getimagesize($from);

        $image = null;

        switch( strtolower($info['mime']) )
        {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($from);
                break;
                
            case 'image/gif':
                $image = imagecreatefromgif($from);
                break;

            case 'image/png':
                $image = imagecreatefrompng($from);
                break;
        }

        return imagejpeg( $image, $to, $quality );
    }
}

if( ! function_exists("getDataType") )
{
    function getDataType($string)
    {
        $type='';

        if(ctype_digit($string))
        {
            $type="bigint";
        }else
        {
            $type="varchar";
        }

            // Try to convert the string to a float
        $floatVal = floatval($string);
        // If the parsing succeeded and the value is not equivalent to an int
        if($floatVal && intval($floatVal) != $floatVal)
        {
            $type="float";

        }

        return $type;

    }
}

if( ! function_exists("removeFileOfStorage") )
{
    function removeFileOfStorage(string $path, bool $public = true):void
    {
        if( $public )
            $path = str_replace("storage","public", $path);

        Storage::delete( $path );
    }
}


if( ! function_exists("removeDirectoryOfStorage") )
{
    function removeDirectoryOfStorage(string $path, bool $public = true):void
    {
        if( $public )
            $path = str_replace("storage","public", $path);

        Storage::deleteDirectory( $path );
    }
}


if( ! function_exists("stripAccents") )
{
    function stripAccents(string $str):string
    {
        return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    }
}

if( ! function_exists('updateConnectionSchema') )
{
    function updateConnectionSchema($name)
    {
        Config::set('database.connections.pgsql.search_path', $name);
        DB::purge('pgsql');
    }
}

if( ! function_exists('setDefaultConnectionSchema') )
{
    function setDefaultConnectionSchema()
    {
        Config::set('database.connections.pgsql.search_path', 'public');
        DB::purge('pgsql');
    }
}

if( ! function_exists("adjustBrightness") )
{
    /**
     * Increases or decreases the brightness of a color by a percentage of the current brightness.
     *
     * @param   string  $hexCode        Supported formats: `#FFF`, `#FFFFFF`, `FFF`, `FFFFFF`
     * @param   float   $adjustPercent  A number between -1 and 1. E.g. 0.3 = 30% lighter; -0.4 = 40% darker.
     *
     * @return  string
     *
     * @author  maliayas
     */
    function adjustBrightness(string $hexCode, float $adjustPercent):string 
    {
        $hexCode = ltrim($hexCode, '#');
    
        if (strlen($hexCode) == 3) {
            $hexCode = $hexCode[0] . $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2];
        }
    
        $hexCode = array_map('hexdec', str_split($hexCode, 2));
    
        foreach ($hexCode as & $color) {
            $adjustableLimit = $adjustPercent < 0 ? $color : 255 - $color;
            $adjustAmount = ceil($adjustableLimit * $adjustPercent);
    
            $color = str_pad(dechex($color + $adjustAmount), 2, '0', STR_PAD_LEFT);
        }
    
        return '#' . implode($hexCode);
    }
}

if( ! function_exists('getRandomColorPart') )
{
    function getRandomColorPart()
    {
        return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    }
}

if( ! function_exists('getRandomColor') )
{
    function getRandomColor()
    {
        return "#". getRandomColorPart() . getRandomColorPart() . getRandomColorPart();
    }
}

