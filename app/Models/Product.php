<?php

namespace App\Models;

use App\Traits\QueryBuilderTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\QueryBuilder\AllowedFilter;

class Product extends Model
{
    use HasFactory;
    use QueryBuilderTrait;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'price',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Получить список доступных фильтров.
     *
     * @return array
     */
    public function getAllowedFilters(): array
    {
        return [
            'title',
            'categories.title',
            $this->setDigitFilter('price'),
            AllowedFilter::exact('active'),
            AllowedFilter::trashed(),
            AllowedFilter::exact('categories.id'),
        ];
    }

    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
