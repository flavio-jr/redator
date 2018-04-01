<?php

namespace App\Filters;

interface FilterInterface
{
    /**
     * Set the values to filter
     * @param array $requestParameters
     * @return self
     */
    public function setFilters(array $requestParameters): FilterInterface;

    /**
     * Executes the query and return the results
     * @return array
     */
    public function get(): array;
}