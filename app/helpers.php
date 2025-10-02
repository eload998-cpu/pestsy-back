<?php

use App\Exceptions\InvalidImageException;
use App\Models\Administration\UserSubscription;
use App\Models\Status;
use App\Models\StatusType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (! function_exists("generate_jwt")) {
    function generate_jwt(): String
    {
        $signing_key = "changeme";
        $header      = [
            "alg" => "HS512",
            "typ" => "JWT",
        ];
        $header  = base64_url_encode(json_encode($header));
        $payload = [
            "exp" => Carbon::parse(Carbon::now())->addHours(1),
        ];
        $payload   = base64_url_encode(json_encode($payload));
        $signature = base64_url_encode(hash_hmac('sha512', "$header.$payload", $signing_key, true));
        $jwt       = "$header.$payload.$signature";
        return $jwt;
    }

    /**
     * per https://stackoverflow.com/questions/2040240/php-function-to-generate-v4-uuid/15875555#15875555
     */
    function base64_url_encode($text): String
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($text));
    }
}

if (! function_exists('jwt_token_is_expired')) {
    function jwt_token_is_expired($token)
    {
        $dataRaw = explode(".", $token);
        $dataRaw = base64_decode($dataRaw[1]);
        $now     = Carbon::parse(Carbon::now());
        $dataRaw = json_decode($dataRaw);
        $exp     = Carbon::parse($dataRaw->exp);

        if ($now->greaterThan($exp)) {
            return true;
        } else {
            return false;

        }
    }
}

if (! function_exists("parsePaginator")) {
    function parsePaginator($paginator)
    {

        $data         = $paginator->items();
        $current_page = $paginator->currentPage();
        $link_size    = 4;
        $to           = $current_page + $link_size;
        $from         = $to - $link_size;
        $lastPage     = $paginator->lastPage();
        $perPage      = $paginator->perPage();
        $total        = $paginator->total();
        $links        = [];

        if (count($data)) {

            if ($to >= $lastPage) {
                $from = $lastPage - $link_size;
            }

            $links = $paginator->getUrlRange(($from <= 0) ? 1 : $from, $to);
            $arr   = [];

            $to_first_page =
                [
                "url"      => $paginator->url(1),
                "label"    => "«",
                "active"   => false,
                "disabled" => ($current_page == 1) ? true : false,

            ];

            $previous_page = [
                "url"      => $paginator->previousPageUrl(),
                "label"    => "‹",
                "active"   => false,
                "disabled" => false,

            ];

            foreach ($links as $link) {
                $parts = parse_url($link);
                parse_str($parts['query'], $query);
                $page = $query['page'];

                if ($page <= $lastPage) {
                    $link_arr =
                        [
                        "url"      => $link,
                        "label"    => $page,
                        "active"   => ($page == $current_page) ? true : false,
                        "disabled" => false,
                    ];

                    array_push($arr, $link_arr);

                }

            }

            $next_page =
                [
                "url"      => $paginator->nextPageUrl(),
                "label"    => "›",
                "active"   => false,
                "disabled" => false,

            ];

            $to_last_page =
                [
                "url"      => $paginator->url($lastPage),
                "label"    => "»",
                "active"   => false,
                "disabled" => ($current_page == $lastPage) ? true : false,
            ];
            array_unshift($arr, $previous_page);
            array_unshift($arr, $to_first_page);

            array_push($arr, $next_page);
            array_push($arr, $to_last_page);

            $links = $arr;

        }

        return compact('current_page', 'data', 'links', 'perPage', 'total', 'lastPage');

    }
}

if (! function_exists("expiredAccountMessage")) {

    function expiredAccountMessage()
    {
        $status_type  = StatusType::where('name', 'plan')->first();
        $status       = Status::where('status_type_id', $status_type->id)->where('name', 'inactive')->first();
        $rol          = Auth::user()->roles()->first()->name;
        $subscription = UserSubscription::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->get()->first();

        if ($subscription->status_id == $status->id) {
            $message = "";
            switch ($rol) {
                case 'administrator':
                    $message = "Cuenta expirada, por favor renueva tu plan";
                    break;

                case 'fumigator':
                    $message = "Cuenta expirada, por favor renueva tu plan";
                    break;

                default:
                    $message = 'Cuenta expirada, por favor comuniquese con el administrador';

                    break;
            }
            $validator = \Validator::make([], []);
            $validator->errors()->add('Error', $message);
            throw new \Illuminate\Validation\ValidationException($validator);
        }

    }
}

if (! function_exists('saveFileInStorageAndReturnPath')) {
    function saveFileInStorageAndReturnPath(UploadedFile $file, string $path, bool $public = true): string
    {
        $fileName = Str::random(15) . '_' . now()->format('d-m-Y') . '.jpg';

        if ($public) {
            Storage::disk('public')->makeDirectory($path);
            $dest = storage_path("app/public/{$path}/{$fileName}");
        } else {
            Storage::makeDirectory($path);
            $dest = storage_path("app/{$path}/{$fileName}");
        }

        optimizeImage($file, $dest, 60);

        return $public
            ? "storage/{$path}/{$fileName}"
            : "{$path}/{$fileName}";
    }
}

if (! function_exists('optimizeImage')) {
    function optimizeImage(UploadedFile $from, string $to, int $quality = 80): bool
    {
        $srcPath = $from->getRealPath();
        if ($srcPath === false || ! is_file($srcPath)) {
            throw new InvalidImageException('Uploaded file is not readable.');
        }

        $info = @getimagesize($srcPath);
        if (! $info || empty($info['mime'])) {
            throw new InvalidImageException('Unsupported or unreadable image.');
        }

        switch (strtolower($info['mime'])) {
            case 'image/jpeg':
                $img = imagecreatefromjpeg($srcPath);
                break;
            case 'image/png':
                $img = imagecreatefrompng($srcPath);
                break;
            case 'image/gif':
                $img = imagecreatefromgif($srcPath);
                break;

            case 'image/webp':
                if (! function_exists('imagecreatefromwebp')) {
                    throw new InvalidImageException('WebP not supported in GD.');
                }
                $img = imagecreatefromwebp($srcPath);
                $ext = 'webp';
                break;
            default:
                throw new InvalidImageException('Only JPG/PNG/GIF/WebP are supported.');
        }
        if (! $img) {
            throw new InvalidImageException('GD failed to load image.');
        }

        $w      = imagesx($img);
        $h      = imagesy($img);
        $canvas = imagecreatetruecolor($w, $h);
        $white  = imagecolorallocate($canvas, 255, 255, 255);
        imagefilledrectangle($canvas, 0, 0, $w, $h, $white);
        imagecopy($canvas, $img, 0, 0, 0, 0, $w, $h);
        imagedestroy($img);

        $ok = imagejpeg($canvas, $to, $quality);
        imagedestroy($canvas);

        return (bool) $ok;
    }
}

if (! function_exists("getDataType")) {
    function getDataType($string)
    {
        $type = '';

        if (ctype_digit($string)) {
            $type = "bigint";
        } else {
            $type = "varchar";
        }

        // Try to convert the string to a float
        $floatVal = floatval($string);
        // If the parsing succeeded and the value is not equivalent to an int
        if ($floatVal && intval($floatVal) != $floatVal) {
            $type = "float";

        }

        return $type;

    }
}

if (! function_exists("removeFileOfStorage")) {
    function removeFileOfStorage(string $path, bool $public = true): void
    {
        if ($public) {
            $path = str_replace("storage", "public", $path);
        }

        Storage::delete($path);
    }
}

if (! function_exists("removeDirectoryOfStorage")) {
    function removeDirectoryOfStorage(string $path, bool $public = true): void
    {
        if ($public) {
            $path = str_replace("storage", "public", $path);
        }

        Storage::deleteDirectory($path);
    }
}

if (! function_exists("stripAccents")) {
    function stripAccents(string $str): string
    {
        return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    }
}

if (! function_exists('updateConnectionSchema')) {
    function updateConnectionSchema($name)
    {
        Config::set('database.connections.pgsql.search_path', $name);
        DB::purge('pgsql');
    }
}

if (! function_exists('setDefaultConnectionSchema')) {
    function setDefaultConnectionSchema()
    {
        Config::set('database.connections.pgsql.search_path', 'public');
        DB::purge('pgsql');
    }
}

if (! function_exists("adjustBrightness")) {
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
    function adjustBrightness(string $hexCode, float $adjustPercent): string
    {
        $hexCode = ltrim($hexCode, '#');

        if (strlen($hexCode) == 3) {
            $hexCode = $hexCode[0] . $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2];
        }

        $hexCode = array_map('hexdec', str_split($hexCode, 2));

        foreach ($hexCode as &$color) {
            $adjustableLimit = $adjustPercent < 0 ? $color : 255 - $color;
            $adjustAmount    = ceil($adjustableLimit * $adjustPercent);

            $color = str_pad(dechex($color + $adjustAmount), 2, '0', STR_PAD_LEFT);
        }

        return '#' . implode($hexCode);
    }
}

if (! function_exists('getRandomColorPart')) {
    function getRandomColorPart()
    {
        return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
    }
}

if (! function_exists('getRandomColor')) {
    function getRandomColor()
    {
        return "#" . getRandomColorPart() . getRandomColorPart() . getRandomColorPart();
    }
}

if (! function_exists('generateRandomString')) {
    function generateRandomString($length = 10)
    {
        $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString     = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if (! function_exists('parse_signed_request')) {

    function parse_signed_request($signed_request)
    {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        $secret = env('FACEBOOK_CLIENT_SECRET');

        // decode the data
        $sig  = base64_url_decode($encoded_sig);
        $data = json_decode(base64_url_decode($payload), true);

        // confirm the signature
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig) {
            error_log('Bad Signed JSON signature!');
            return null;
        }

        return $data;
    }
}

if (! function_exists('base64_url_decode')) {

    function base64_url_decode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }
}

if (! function_exists('generate_bill_code')) {

    function generate_bill_code($length = 10)
    {
        $characters       = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomBillCode   = '';

        for ($i = 0; $i < $length; $i++) {
            $randomBillCode .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomBillCode;
    }

}
