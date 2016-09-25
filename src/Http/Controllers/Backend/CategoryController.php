<?php

namespace ZEDx\Http\Controllers\Backend;

use Image;
use Intervention\Image\Exception\NotReadableException;
use Request;
use ZEDx\Events\Category\CategoryWasUpdated;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Http\Requests\CategoryRequest;
use ZEDx\Models\Category;
use ZEDx\Models\Field;

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

        $parent_id = Request::get('parent_id');

        return view_backend('category.create', compact('fields', 'parent_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryRequest $request
     *
     * @return Response
     */
    public function store(CategoryRequest $request)
    {
        $input = $request->all();
        $category = Category::create($input);

        $this->saveThumbnail($category, $request);
        $this->setCategoryParent($category, $request);

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
     * Save thumbnail.
     *
     * @param CategoryRequest $request
     * @param Category        $category
     *
     * @return void
     */
    protected function saveThumbnail(Category $category, CategoryRequest $request)
    {
        if (!$request->hasFile('thumbnail')) {
            return;
        }
        $image = $request->file('thumbnail');
        $name = $category->id.'.png';
        $path = public_path('uploads/categories/'.$name);

        try {
            $img = Image::make($image);
        } catch (NotReadableException $e) {
            return;
        }

        $img->save($path, 100);

        $category->thumbnail = $name;
        $category->save();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit(Category $category)
    {
        $codes = $category->codes;
        $selectedFieldsId = array_reverse($category->fields()->lists('fields.id')->toArray());

        $fields = Field::whereNotIn('id', $selectedFieldsId)->get();
        $parent_id = $category->parent_id;

        return view_backend('category.edit', compact('category', 'codes', 'fields', 'parent_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update(Category $category, CategoryRequest $request)
    {
        $input = $request->all();
        $category->update($input);

        $this->saveThumbnail($category, $request);

        $this->setCategoryParent($category, $request);

        $this->saveCategoryFields($category, $request);

        $category->codes()->delete();
        if ($request->get('is_private') == '1') {
            $this->saveCategoryCodes($category, $request);
        }

        event(new CategoryWasUpdated($category));

        return redirect()->route('zxadmin.category.edit', $category->id)->with('message', 'success');
    }

    protected function setCategoryParent($category, $request)
    {
        if (!$request->parent_id) {
            $category->makeRoot();

            return;
        }

        if ($request->parent_id == $category->id) {
            return;
        }

        $parent = Category::find($request->parent_id);

        if ($parent) {
            $category->makeChildOf($parent);
        }
    }

    /**
     * Order the specified resource in storage.
     *
     * @param int $id
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
     * @param Category $category
     *
     * @return Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
    }
}
