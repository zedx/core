<?php

namespace ZEDx\Http\Controllers\Backend;

use ZEDx\Models\Adtype;
use Illuminate\Support\Collection;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Http\Requests\AdtypeRequest;

class AdtypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $adtypes = Adtype::notCustomized()->paginate(10);

        return view_backend('adtype.index', compact('adtypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view_backend('adtype.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(AdtypeRequest $request)
    {
        $inputs = $this->getAdtypeInputs($request);
        $adtype = Adtype::create($inputs);

        return redirect()->route('zxadmin.adtype.edit', $adtype->id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit(Adtype $adtype)
    {
        return view_backend('adtype.edit', compact('adtype'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function update(Adtype $adtype, AdtypeRequest $request)
    {
        $inputs = $this->getAdtypeInputs($request);
        $adtype->update($inputs);

        return redirect()->route('zxadmin.adtype.edit', $adtype->id)->with('message', 'success');
    }

    protected function getAdtypeInputs($request)
    {
        $inputs = $request->all();

        $inputs['nbr_pic'] = $request->get('can_add_pic') && $inputs['nbr_pic'] > 0 ? $inputs['nbr_pic'] : 0;
        $inputs['can_update_pic'] = $inputs['nbr_pic'] > 0 ? $inputs['can_update_pic'] : 0;
        $inputs['can_add_pic'] = $inputs['nbr_pic'] > 0 ? $inputs['can_add_pic'] : 0;
        $inputs['nbr_video'] = $request->get('can_add_video') && $inputs['nbr_video'] > 0 ? $inputs['nbr_video'] : 0;
        $inputs['can_update_video'] = $inputs['nbr_video'] > 0 ? $inputs['can_update_video'] : 0;
        $inputs['can_add_video'] = $inputs['nbr_video'] > 0 ? $inputs['can_add_video'] : 0;

        return $inputs;
    }

    /**
     * Remove a Collection of Adtypes.
     *
     * @param  Collection  $adtypes
     *
     * @return Response
     */
    public function destroyAdtypesCollection(Collection $adtypes)
    {
        foreach ($adtypes as $adtype) {
            $this->destroy($adtype);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Adtype  $adtype
     *
     * @return Response
     */
    protected function destroy(Adtype $adtype)
    {
        $adtype->delete();
    }
}
