<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Requests\Admin\V1\StoreBankRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateBankRequest;
use Whatsloan\Jobs\StoreAddressJob;
use Whatsloan\Jobs\StoreBankJob;
use Whatsloan\Jobs\UpdateBankJob;
use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Repositories\Types\Type;
use Whatsloan\Repositories\Banks\BankProduct;
use Whatsloan\Jobs\UpdateBankPictureJob;

class BanksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banks = Bank::with(['addresses', 'addresses.city'])
                        ->withTrashed()
                        ->orderBy('banks.deleted_at','asc')
                        ->paginate();
        return view('admin.v1.banks.index')->withBanks($banks);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.banks.index')->withError("Access Restricted");
        }
        return view('admin.v1.banks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreBankRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBankRequest $request)
    {
        $bank = $this->dispatch(new StoreBankJob($request));
        if ($request->exists('bank_picture')) {
            $upload = upload($bank->getBankPath(), $request->file('bank_picture'));
            $request->offsetSet('attachment', $upload);
        }
        $this->dispatch(new UpdateBankPictureJob($bank->id, $request->except(['bank_picture'])));
        return redirect()->route('admin.v1.banks.index')->withSuccess('Bank added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bank_products =[];
        $type = Type::lists('key')->toArray();
        $bank = Bank::with(['addresses', 'addresses.city', 'projects','products','users'])
                        ->with(['attachments' => function($query) use($type) {
                            $query->whereIn('type', ['CHECKLIST','BANK_PICTURE']);
                        }])->withTrashed()->find($id);
        $selected_bank_product = Bank::with('products')->whereId($id)->withTrashed()->first();
        foreach ($selected_bank_product->products as $product) {
            $bank_product_IDS[] = $product->pivot->id;
        }
        if(!empty($bank_product_IDS)){
            $bank_products = BankProduct::with(['attachments'])
                            ->whereIn('id',$bank_product_IDS)->get();
        }
        return view('admin.v1.banks.show')->withBank($bank)->withProducts($bank_products);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.banks.index')->withError("Access Restricted");
        }

        $bank = Bank::with(['addresses', 'addresses.city'])
                ->withTrashed()
                ->find($id);
        return view('admin.v1.banks.edit')->withBank($bank);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UpdateBankRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBankRequest $request, $id)
    {
        $bank = Bank::find($id);
        if ($request->exists('bank_picture')) {
            $upload = upload($bank->getBankPath(), $request->file('bank_picture'));
            $request->offsetSet('attachment', $upload);
        }
        $this->dispatch(new UpdateBankJob($id, $request->except(['bank_picture'])));
        return redirect()->route('admin.v1.banks.show', $id)->withSuccess('Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.banks.index')->withError("Access Restricted");
        }
        $bank = Bank::withTrashed()->findOrFail($id);
        $bank->trashed() ? $bank->restore() : $bank->delete();
        return redirect()->route('admin.v1.banks.index')->withSuccess('Bank updated successfully');
    }
}
