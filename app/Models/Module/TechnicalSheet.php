<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicalSheet extends Model
{
    use HasFactory;

    protected $table = "modules.technical_sheets";

    protected $fillable =
        [
        "name",
        "file_url",
        "company_id",
    ];

    protected function fileUrl(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return config('app.url') . $value;
            }
        );
    }

}
