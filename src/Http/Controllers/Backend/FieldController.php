<?php

namespace ZEDx\Http\Controllers\Backend;

use Illuminate\Support\Collection;
use ZEDx\Events\Field\FieldWasCreated;
use ZEDx\Events\Field\FieldWasUpdated;
use ZEDx\Events\Field\FieldWillBeCreated;
use ZEDx\Events\Field\FieldWillBeUpdated;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Http\Requests\FieldRequest;
use ZEDx\Models\Field;
use ZEDx\Models\SelectField;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $fields = Field::paginate(10);

        return view_backend('field.index', compact('fields'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view_backend('field.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(FieldRequest $request)
    {
        $field = new Field();
        $field->fill($request->all());

        event(
            new FieldWillBeCreated($field)
        );

        $field->save();

        if (in_array($request->get('type'), [1, 2, 3])) {
            $this->syncFieldOptions($field, $request->get('options'));
        } else {
            if ($request->get('type') == 4) {
                $field->search()->create($request->get('search'));
            }
        }

        event(
            new FieldWasCreated($field)
        );

        return redirect()->route('zxadmin.field.edit', $field->id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit(Field $field)
    {
        $options = $field->select()->sorted()->get();
        $search = $field->search()->get();

        return view_backend('field.edit', compact('field', 'options', 'search'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function update(Field $field, FieldRequest $request)
    {
        $field->fill($request->all());

        event(
            new FieldWillBeUpdated($field)
        );

        $field->save();

        if (in_array($request->get('type'), [1, 2, 3])) {
            $this->syncFieldOptions($field, $request->get('options'));
        } else {
            $field->select()->delete();
            if ($request->get('type') == 4) {
                $field->search()->update($request->get('search'));
            }
        }

        event(
            new FieldWasUpdated($field)
        );

        return redirect()->back()->with('message', 'success');
    }

    /**
     * Sync Field Options.
     *
     * @param  Field  $field
     * @param  array  $options
     *
     * @return void
     */
    protected function syncFieldOptions(Field $field, $options)
    {
        if (! $options) {
            $options = [];
        }

        $newIds = [];
        $existingIds = $field->select()->lists('id')->toArray();
        $position = 1;
        foreach ($options as $id => $name) {
            if ($name != '') {
                if (preg_match('/^fn\\d+$/', $id)) {
                    $field->select()->create(['name' => $name, 'position' => $position]);
                } elseif (preg_match("/^f(?<id>\d+)$/", $id, $matches)) {
                    $field->select()->findOrFail($matches['id'])->update(['name' => $name, 'position' => $position]);
                    $newIds[] = $matches['id'];
                }
                $position++;
            }
        }

        $removeIds = array_diff($existingIds, $newIds);
        if (! empty($removeIds)) {
            SelectField::destroy($removeIds);
        }
    }

    /**
     * Remove a Collection of Fields.
     *
     * @param  Collection  $fields
     *
     * @return Response
     */
    public function destroyFieldsCollection(Collection $fields)
    {
        foreach ($fields as $field) {
            $this->destroy($field);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Field  $field
     *
     * @return Response
     */
    protected function destroy(Field $field)
    {
        $field->delete();
    }
}
