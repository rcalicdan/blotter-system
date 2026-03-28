<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait WithRelationshipSorting
{
    use WithSorting {
        applySorting as baseApplySorting;
    }

    protected function applySorting(Builder $query): Builder
    {
        if (! $this->sortField || ! $this->isSortable($this->sortField)) {
            return $query;
        }

        if (property_exists($this, 'relationshipSorts') && isset($this->relationshipSorts[$this->sortField])) {
            [$relation, $column] = $this->relationshipSorts[$this->sortField];

            return $query->orderBy(
                $this->getRelationshipSubquery($relation, $column),
                $this->sortDirection
            );
        }

        return $this->baseApplySorting($query);
    }

    protected function getRelationshipSubquery(string $relation, string $column)
    {
        $modelClass = $this->getModelClass();
        $model = new $modelClass;

        $relatedModel = $model->$relation()->getRelated();
        $table = $relatedModel->getTable();
        $foreignKey = $this->getForeignKeyForRelation($relation);

        return $relatedModel::select($column)
            ->whereColumn("{$table}.id", $foreignKey)
            ->limit(1);
    }

    protected function getForeignKeyForRelation(string $relation): string
    {
        // Override this method in your component if needed
        return "{$relation}_id";
    }

    abstract protected function getModelClass(): string;
}
