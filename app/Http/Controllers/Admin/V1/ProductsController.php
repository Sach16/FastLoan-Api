<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests\Admin\V1\StoreProductRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateProductRequest;
use Whatsloan\Jobs\StoreProductJob;
use Whatsloan\Jobs\UpdateProductJob;
use Whatsloan\Repositories\Types\Type;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->back()->withError("Access Restricted");
        }
        $products = Type::with('banks','loans')
                    ->orderBy('deleted_at', 'asc')
                    ->withTrashed()
                    ->paginate();

        return view('admin.v1.products.index')->withProducts($products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->back()->withError("Access Restricted");
        }
        return view('admin.v1.products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreProductRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $request->offsetSet('uuid', uuid());
        $this->dispatch(new StoreProductJob($request->all()));
        return redirect()->route('admin.v1.products.index')->withSuccess('Product added successfully');
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
            return redirect()->back()->withError("Access Restricted");
        }
        $product = Type::withTrashed()->find($id);
        return view('admin.v1.products.edit')->withProduct($product);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|UpdateProductRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $this->dispatch(new UpdateProductJob($request->all(), $id));

        return redirect()->route('admin.v1.products.index')->withSuccess('Product updated successfully');
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
            return redirect()->back()->withError("Access Restricted");
        }
        $product = Type::withTrashed()->findOrFail($id);
        $product->trashed() ? $product->restore() : $product->delete();

        return redirect()->back()->withSuccess('Product updated successfully');
    }
}
