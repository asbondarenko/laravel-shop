<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Trait QueryBuilderTrait
 *
 * Для подключения нужных полей использовать
 * аналогичные переменные что и здесь только без 'queryBuilderTrait'
 *
 * @package App\Traits
 */
trait QueryBuilderTrait
{
    /** @var string[] $queryBuilderTraitAllowedFilters доступные поля для фильтрации */
    private array $queryBuilderTraitAllowedFilters = [];

    /** @var string[] $queryBuilderTraitAllowedFields доступные поля для выборки */
    private array $queryBuilderTraitAllowedFields = [];

    /** @var string[] $queryBuilderTraitAllowedAppends доступные аксессоры для выборки */
    private array $queryBuilderTraitAllowedAppends = [];

    /**
     * @var string[] $queryBuilderTraitAllowedRelations доступные связи для выборки
     *      что бы они были доступны надо добавить ключи выше
     */
    private array $queryBuilderTraitAllowedRelations = [];

    /** @var string[] $queryBuilderTraitAllowedSorts доступные поля для сортировок */
    private array $queryBuilderTraitAllowedSorts = [];

    /**
     * Собирает все переменные модели
     *
     * @return QueryBuilder
     */
    public static function makeBuilder(): QueryBuilder
    {
        $builder = QueryBuilder::for(self::class)->defaultSort('-created_at');
        $object = (new self);
        $search = request()->query('search');
        if (!empty($search) && $builder->hasNamedScope('search')) {
            $builder->search($search);
        }
        if ($object->queryBuilderTraitAllowedFields) {
            $builder->allowedFields($object->queryBuilderTraitAllowedFields);
        }
        if ($object->queryBuilderTraitAllowedAppends) {
            $builder->allowedAppends($object->queryBuilderTraitAllowedAppends);
        }
        if ($object->queryBuilderTraitAllowedFilters) {
            $builder->allowedFilters($object->queryBuilderTraitAllowedFilters);
        }
        if ($object->queryBuilderTraitAllowedSorts) {
            $builder->allowedSorts($object->queryBuilderTraitAllowedSorts);
        }
        if ($object->queryBuilderTraitAllowedRelations) {
            $builder->allowedIncludes($object->queryBuilderTraitAllowedRelations);
        }

        return $builder;
    }

    /**
     * При загрузке если не указаны фильтруемые поля,
     * поля для селекта и поля для сортинга
     * то создать их из (филабла - хидден)
     */
    protected function initializeQueryBuilderTrait(): void
    {
        $availableFields = array_diff($this->getFillable(), $this->getHidden());
        $this->queryBuilderTraitAllowedFields = $this->allowedFields ?? $availableFields;
        $this->queryBuilderTraitAllowedAppends = $this->allowedAppends ?? [];
        if (method_exists($this, 'getAllowedFilters')) {
            $this->queryBuilderTraitAllowedFilters = $this->getAllowedFilters();
        } else {
            $this->queryBuilderTraitAllowedFilters = $this->allowedFilters ?? $availableFields;
        }

        if (method_exists($this, 'getAllowedSorts')) {
            $this->queryBuilderTraitAllowedSorts = $this->getAllowedSorts();
        } else {
            $this->queryBuilderTraitAllowedSorts = $this->allowedSorts ?? $availableFields;
        }
        $this->queryBuilderTraitAllowedRelations = $this->allowedRelations ?? [];
    }

    /**
     * Установить числовой фильтр
     *
     * @param $column
     *
     * @return AllowedFilter
     */
    public function setDigitFilter($column): AllowedFilter
    {
        return AllowedFilter::callback($column, static function (Builder $builder, $value) use ($column) {
            return $builder->betweenFilter($column, $value);
        });
    }

    /**
     * @param Builder $builder
     * @param string $column
     * @param string $value_raw
     *
     * @return Builder
     */
    public function scopeBetweenFilter(Builder $builder, string $column, string $value_raw): Builder
    {
        if (str_contains($value_raw, ':')) {
            $value = explode(':', $value_raw);

            return $builder->whereBetween($column, $value);
        }

        $operator = '=';
        if (str_contains($value_raw, '=')) {
            $operator = substr($value_raw, 0, 2);
        } elseif (str_contains($value_raw, '>') || str_contains($value_raw, '<')) {
            $operator = substr($value_raw, 0, 1);
        }

        $value = preg_replace('/[^\d-]/', '', $value_raw);

        return $builder->where($column, $operator, $value);
    }
}
