<?php

namespace ZEDx\Http\Controllers\Backend;

use Illuminate\Http\Request;
use ZEDx\Events\Menu\MenuWasCreated;
use ZEDx\Events\Menu\MenuWasUpdated;
use ZEDx\Events\Menu\MenuWillBeCreated;
use ZEDx\Events\Menu\MenuWillBeUpdated;
use ZEDx\Http\Controllers\Controller;
use ZEDx\Http\Requests\MenuRequest;
use ZEDx\Models\Menu;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->filterByGroupName('header');
    }

    /**
     * Display a listing of the resource by group.
     *
     * @return \Illuminate\Http\Response
     */
    public function filterByGroupName($groupName)
    {
        $groups = $this->getGroups();
        $menus = Menu::whereGroupName($groupName)
            ->orderBy('lft')
            ->get()
            ->toHierarchy();

        return view_backend('menu.index', compact('menus', 'groupName', 'groups'));
    }

    protected function getGroups()
    {
        $defaultGroupList = $this->getDefaultGroupList();
        $currentGroupList = Menu::distinct()->select('group_name')
            ->whereNotIn('group_name', array_keys($defaultGroupList))
            ->lists('group_name')
            ->toArray();

        return array_unique(array_merge($defaultGroupList, array_combine($currentGroupList, $currentGroupList)));
    }

    protected function getDefaultGroupList()
    {
        return [
            'header'      => trans('backend.menu.group.header'),
            'footer'      => trans('backend.menu.group.footer'),
            'user'        => trans('backend.menu.group.user'),
            'user-header' => trans('backend.menu.group.user-header'),
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(MenuRequest $request)
    {
        $menu = new Menu();
        $menu->fill($request->all());

        event(
            new MenuWillBeCreated($menu)
        );

        $menu->save();

        event(
            new MenuWasCreated($menu)
        );

        return back();
    }

    /**
     * Order the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function order(Menu $menu, Request $request)
    {
        if ($left_id = $request->get('leftId')) {
            $cat_left = Menu::findOrFail($left_id);
            $menu->moveToRightOf($cat_left);
        } elseif ($right_id = $request->get('rightId')) {
            $cat_right = Menu::findOrFail($right_id);
            $menu->moveToLeftOf($cat_right);
        } elseif ($parent_id = $request->get('parentId')) {
            $cat_parent = Menu::findOrFail($parent_id);
            $menu->makeChildOf($cat_parent);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Menu $menu, Request $request)
    {
        $menu->fill($request->all());

        event(
            new MenuWillBeUpdated($menu)
        );

        $menu->save();

        event(
            new MenuWasUpdated($menu)
        );

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
    }
}
