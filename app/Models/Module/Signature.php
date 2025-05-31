<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Signature extends Model
{
    use HasFactory;

    protected $table="modules.signatures";

    protected $fillable=
    [
        "order_id",
        "client_signature_url",
        "worker_signature_url"
    ];

    protected $appends = [
        "full_client_signature_url",
        "full_worker_signature_url"
    ];


    public function getFullCLientSignatureUrlAttribute():string
    {
        return config('app.url').$this->attributes["client_signature_url"];
    }

    public function getFullWorkerSignatureUrlAttribute():string
    {
        return config('app.url').$this->attributes["worker_signature_url"];
    }

    /*
    
    protected function clientSignatureUrl(): Attribute
    {
        return Attribute::make(
            get: function($value){
                return config('app.url').$value;
            }
        );
    }

    
    protected function workerSignatureUrl(): Attribute
    {
        return Attribute::make(
            get: function($value){
                return config('app.url').$value;
            }
        );
    }*/
}
