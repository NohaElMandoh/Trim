<?php

namespace Modules\Category\Entities;

use League\Fractal;
use Modules\Category\Entities\Category;

class CategoryTransformer extends Fractal\TransformerAbstract
{
    public function transform(Category $category)
    {
        return [
            'id'        => (int) $category->id,
            'name'      => $category->name,
        ];
    }
}
