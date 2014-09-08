<?php namespace App\LoyaltyCard\Controllers;
use Input, Session, Redirect, View, Validator;
use \App\LoyaltyCard\Models\Voucher as VoucherModel;
use Confide;

class Voucher extends \App\Core\Controllers\Base {

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        // get all the vouchers
        $vouchers = VoucherModel::where('user_id', '=', Confide::user()->id)->paginate(10);

        // load the view and pass the vouchers
        return View::make('modules.lc.vouchers.index')
            ->with('vouchers', $vouchers);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return View::make('modules.lc.vouchers.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Redirect
     */
    public function store()
    {
        $rules = [
            'name'          => 'required',
            'required'      => 'required|numeric',
            'value'         => 'required|numeric',
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $voucher = new VoucherModel;
            $voucher->name = Input::get('name');
            $voucher->user_id = Confide::user()->id;
            $voucher->required = Input::get('required');
            $voucher->value = Input::get('value');
            $voucher->total_used = 0;
            $voucher->type = Input::get('type');
            $voucher->is_active = Input::get('active');
            $voucher->save();

            return Redirect::route('lc.vouchers.index');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function show($id)
    {
        $voucher = VoucherModel::find($id);

        return View::make('modules.lc.vouchers.show')
            ->with('voucher', $voucher);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $voucher = VoucherModel::find($id);

        return View::make('modules.lc.vouchers.edit')
            ->with('voucher', $voucher);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Redirect
     */
    public function update($id)
    {
        $rules = [
            'name'      => 'required',
            'required'  => 'required|numeric',
            'value'     => 'required|numeric',
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $voucher = VoucherModel::find($id);
            $voucher->name = Input::get('name');
            $voucher->required = Input::get('required');
            $voucher->value = Input::get('value');
            $voucher->type = Input::get('type');
            $voucher->is_active = Input::get('is_active');
            $voucher->save();

            return Redirect::route('lc.vouchers.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Redirect
     */
    public function destroy($id)
    {
        $voucher = VoucherModel::find($id);
        $voucher->delete();

        return Redirect::route('lc.vouchers.index');
    }
}
