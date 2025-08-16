<?php
namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XylophageControl extends Model
{
    use HasFactory;

    protected $table = "modules.control_of_xylophages";

    protected $fillable =
        [
        "order_id",
        "pest_id",
        "product_id",
        "construction_type_id",
        "affected_element_id",
        "infestation_level",
        "observation",
        "treatment_date",
        "next_treatment_date",
        "treated_area_value",
        "treated_area_unit",
        "calculated_total_amount",
        "calculated_total_unit",
        "pre_humidity",
        "pre_ventilation",
        "pre_access",
        "pre_notes",
        "post_humidity",
        "post_ventilation",
        "post_access",
        "post_notes",
        "dose",
        "location_id",
        "worker_id",
        "aplication_id",
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function pest()
    {
        return $this->belongsTo(Pest::class);
    }

    public function application()
    {
        return $this->belongsTo(Aplication::class);
    }

    public function constructionType()
    {
        return $this->belongsTo(ConstructionType::class);
    }

    public function affectedElement()
    {
        return $this->belongsTo(AffectedElement::class);
    }

}
