<?php

namespace ZEDx\Http\Controllers\ZxAjax;

use ZEDx\Http\Controllers\Controller;
use ZEDx\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function adFields(Category $category)
    {
        if (\Request::ajax() && $category->has('fields')) {
            return $category
                    ->fields()
                    ->whereIsInAd('1')
                    ->with([
                        'select' => function ($query) {
                            $query->orderBy('position', 'asc');
                        },
                    ])
                    ->get();
        } else {
            abort(404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function searchFields(Category $category)
    {
        if (\Request::ajax() && $category->has('fields')) {
            return $category
                    ->fields()
                    ->whereIsInSearch('1')
                    ->with([
                        'search',
                        'select' => function ($query) {
                            $query->orderBy('position', 'asc');
                        },
                    ])
                    ->get();
        } else {
            abort(404);
        }
    }
}
