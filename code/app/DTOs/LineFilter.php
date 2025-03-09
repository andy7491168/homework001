<?php

namespace App\DTOs;

class LineFilter
{
    public $search;
    public $filterField;
    public $filterOperator;
    public $filterValue;
    public $sortField;
    public $sortDirection;

    public function __construct(
        $search = null,
        $filterField = null,
        $filterOperator = null,
        $filterValue = null,
        $sortField = null,
        $sortDirection = null
    ) {
        $this->search = $search;
        $this->filterField = $filterField;
        $this->filterOperator = $filterOperator;
        $this->filterValue = $filterValue;
        $this->sortField = $sortField;
        $this->sortDirection = $sortDirection;
    }
}