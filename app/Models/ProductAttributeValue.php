<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    protected $fillable = [
        'product_id',
        'attribute_id',
        'attribute_value_id',
        'value_text',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class);
    }

    // Accessor for getting the actual value
    public function getValueAttribute()
    {
        if ($this->value_text) {
            return $this->value_text;
        }

        return $this->attributeValue ? $this->attributeValue->value : null;
    }
}
