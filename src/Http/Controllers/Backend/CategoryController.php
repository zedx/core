<?php

namespace ZEDx\Http\Controllers\Backend;

use Request;
use ZEDx\Models\Field;
use ZEDx\Models\Category;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Http\Requests\CategoryRequest;
use ZEDx\Events\Category\CategoryWasUpdated;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $categories = Category::all()->toHierarchy();

        return view_backend('category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $fields = Field::all();

        return view_backend('category.create', compact('fields'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryRequest  $request
     *
     * @return Response
     */
    public function store(CategoryRequest $request)
    {
        $input = $request->all();
        $category = Category::create($input);

        $this->saveCategoryFields($category, $request);

        $category->codes()->delete();
        $this->saveCategoryCodes($category, $request);

        return redirect()->route('zxadmin.category.edit', $category->id);
    }

    protected function saveCategoryFields(Category $category, CategoryRequest $request)
    {
        $category->fields()->detach();
        if ($request->has('fields')) {
            $fields = $request->get('fields');
            $category->fields()->sync($fields);
        }
    }

    protected function saveCategoryCodes(Category $category, CategoryRequest $request)
    {
        if ($request->has('codes')) {
            $codes = $request->get('codes');
            foreach ($codes as $code) {
                $category->codes()->create($code);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit(Category $category)
    {
        $codes = $category->codes;
        $selectedFieldsId = array_reverse($category->fields()->lists('fields.id')->toArray());

        $fields = Field::whereNotIn('id', $selectedFieldsId)->get();

        return view_backend('category.edit', compact('category', 'codes', 'fields'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function update(Category $category, CategoryRequest $request)
    {
        $input = $request->all();
        $category->update($input);

        $this->saveCategoryFields($category, $request);

        $category->codes()->delete();
        if ($request->get('is_private') == '1') {
            $this->saveCategoryCodes($category, $request);
        }

        event(new CategoryWasUpdated($category));

        return redirect()->route('zxadmin.category.edit', $category->id)->with('message', 'success');
    }

    /**
     * Order the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function order(Category $category)
    {
        if ($left_id = Request::get('leftId')) {
            $cat_left = Category::findOrFail($left_id);
            $category->moveToRightOf($cat_left);
        } elseif ($right_id = Request::get('rightId')) {
            $cat_right = Category::findOrFail($right_id);
            $category->moveToLeftOf($cat_right);
        } elseif ($parent_id = Request::get('parentId')) {
            $cat_parent = Category::findOrFail($parent_id);
            $category->makeChildOf($cat_parent);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Category  $category
     *
     * @return Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
    }
}
