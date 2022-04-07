<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SidebarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return response()->json(Role::latest()->get());
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $side = Role::create([
            'name' => $request['name'],
            'link' => $request['link'],
            'icon' => $request['icon'],
        ]);

        return response()->json($side);

    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $side =  Role::findOrFail($id)->update([
            'name' => $request['name'],
            'link' => $request['link'],
            'icon' => $request['icon'],
        ]);

        return response()->json($side);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $side =  Role::findOrFail($id)->delete();
    }
}
