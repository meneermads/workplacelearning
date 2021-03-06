<?php


namespace App\Tips\Statistics\Filters;


use Illuminate\Database\Query\Builder;

class CategoryFilter implements Filter
{

    private $parameters;

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    public function filter(Builder $builder)
    {
        if (empty($this->parameters['category_label'])) {
            return;
        }

        $categories = array_map('trim', explode('||', $this->parameters['category_label']));

        $builder
            ->leftJoin('category', 'learningactivityproducing.category_id', '=', 'category.category_id')
            ->whereIn('category_label', $categories);
    }
}