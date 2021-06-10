<?php

namespace App\Models\Product;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Category
 *
 * @property string $name
 * @property int $sort_order
 * @property Product[] $products
 *
 * @method static Category create(array $category)
 * @package App
 */
class Category extends Model
{
    use SoftDeletes;

    /**
     * relate product table
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function product()
    {
        return $this->belongsToMany('App\Models\Product\Product', 'product_category');
    }
}
