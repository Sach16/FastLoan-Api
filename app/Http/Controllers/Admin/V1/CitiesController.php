<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests\Admin\V1\StoreCityRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateCityRequest;
use Whatsloan\Jobs\StoreCityJob;
use Whatsloan\Jobs\UpdateCityJob;
use Whatsloan\Repositories\Cities\City;

class CitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::with(['addresses'])->withTrashed()->orderBy('deleted_at', 'asc')->paginate();
        return view('admin.v1.cities.index')->withCities($cities);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.banks.index')->withError("Access Restricted");
        }
        return view('admin.v1.cities.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreCityRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCityRequest $request)
    {
        $request->offsetSet('uuid', uuid());
        $this->dispatch(new StoreCityJob($request->all()));
        return redirect()->route('admin.v1.cities.index')->withSuccess('City added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.banks.index')->withError("Access Restricted");
        }
        $city = City::withTrashed()->find($id);
        return view('admin.v1.cities.edit')->withCity($city);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|UpdateCityRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCityRequest $request, $id)
    {
        $this->dispatch(new UpdateCityJob($request->all(), $id));
        return redirect()->route('admin.v1.cities.index')->withSuccess('City updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.cities.index')->withError("Access Restricted");
        }
        $city = City::withTrashed()->find($id);
        $city->trashed() ? $city->restore() : $city->delete();

        return redirect()->route('admin.v1.cities.index')->withSuccess('City updated successfully');
    }
}
