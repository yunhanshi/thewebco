<?php

namespace App\Models\Product;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Product
 *
 * @property string $name
 * @property float $price
 * @property int $sort_order
 * @property Category[] $categories
 *
 * @method static Product create(array $product)
 * @method static Product find(int $id)
 * @package App
 */
class Product extends Model
{
    use SoftDeletes;

    /**
     * relate category table
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function category()
    {
        return $this->belongsToMany('App\Models\Product\Category', 'product_category');
    }
}
