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
        "applied_treatment_id",
        "construction_type_id",
        "affected_element_id",
        "infestation_level",
        "observation",
        "treatment_date",
        "next_treatment_date",
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function pest()
    {
        return $this->belongsTo(Pest::class);
    }

    public function appliedTreatment()
    {
        return $this->belongsTo(AppliedTreatment::class);
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
