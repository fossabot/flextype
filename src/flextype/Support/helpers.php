<?php

declare(strict_types=1);

/**
 * Flextype (http://flextype.org)
 * Founded by Sergey Romanenko and maintained by Flextype Community.
 */

use Flextype\Support\Collection;


if (! function_exists('collection')) {
    /**
     * Create a collection from the given value.
     *
     * @param  mixed $value
     */
    function collection($items) : Collection
    {
        return new Collection($items);
    }
}

if (! function_exists('collection_filter')) {
    /**
     * Create a collection from the given value.
     *
     * @param  mixed $value
     */
    function collection_filter($items, array $filter) : array
    {
        $collection = new Collection($items);

        // Set Expression
        $expression = $collection->expression;

        // Set Direction
        $direction = $collection->direction;

        // Bind: recursive
        $bind_recursive = $filter['recursive'] ?? false;

        // Bind: set first result
        $bind_set_first_result = $filter['set_first_result'] ?? false;

        // Bind: set max result
        $bind_set_max_result = $filter['set_max_result'] ?? false;

        // Bind: where
        $bind_where = [];
        if (isset($filter['where']['key']) && isset($filter['where']['expr']) && isset($filter['where']['value'])) {
            $bind_where['where']['key']   = $filter['where']['key'];
            $bind_where['where']['expr']  = $expression[$filter['where']['expr']];
            $bind_where['where']['value'] = $filter['where']['value'];
        }

        // Bind: and where
        $bind_and_where = [];
        if (isset($filter['and_where'])) {
            foreach ($filter['and_where'] as $key => $value) {
                if (! isset($value['key']) || ! isset($value['expr']) || ! isset($value['value'])) {
                    continue;
                }

                $bind_and_where[$key] = $value;
            }
        }

        // Bind: or where
        $bind_or_where = [];
        if (isset($filter['or_where'])) {
            foreach ($filter['or_where'] as $key => $value) {
                if (! isset($value['key']) || ! isset($value['expr']) || ! isset($value['value'])) {
                    continue;
                }

                $bind_or_where[$key] = $value;
            }
        }

        // Bind: order by
        $bind_order_by = [];
        if (isset($filter['order_by']['field']) && isset($filter['order_by']['direction'])) {
            $bind_order_by['order_by']['field']     = $filter['order_by']['field'];
            $bind_order_by['order_by']['direction'] = $filter['order_by']['direction'];
        }

        // Exec: where
        if (isset($bind_where['where']['key']) && isset($bind_where['where']['expr']) && isset($bind_where['where']['value'])) {
            $collection->where($bind_where['where']['key'], $bind_where['where']['expr'], $bind_where['where']['value']);
        }

        // Exec: and where
        if (isset($bind_and_where)) {
            $_expr = [];
            foreach ($bind_and_where as $key => $value) {
                $collection->andWhere($value['where']['key'], $value['where']['expr'], $value['where']['value']);
            }
        }

        // Exec: or where
        if (isset($bind_or_where)) {
            $_expr = [];
            foreach ($bind_or_where as $key => $value) {
                $collection->orWhere($value['where']['key'], $value['where']['expr'], $value['where']['value']);
            }
        }

        // Exec: order by
        if (isset($bind_order_by['order_by']['field']) && isset($bind_order_by['order_by']['direction'])) {
            $collection->orderBy([$bind_order_by['order_by']['field'] => $direction[$bind_order_by['order_by']['direction']]]);
        }

        // Exec: set max result
        if ($bind_set_max_result) {
            $collection->limit($bind_set_max_result);
        }

        // Exec: set first result
        if ($bind_set_first_result) {
            $collection->setFirstResult($bind_set_first_result);
        }

        // Gets a native PHP array representation of the collection.
        $items = $collection->first();

        // Return entries
        return $items;
    }
}
