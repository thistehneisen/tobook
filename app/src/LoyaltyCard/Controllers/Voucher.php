<?php namespace App\LoyaltyCard\Controllers;
use Input, Session, Redirect, View, Validator;
use \App\LoyaltyCard\Models\Voucher as VoucherModel;
use Confide;

class Voucher extends \App\Core\Controllers\Base {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // get all the vouchers
        $vouchers = VoucherModel::paginate(10);

        // load the view and pass the vouchers
        return View::make('modules.lc.vouchers.index')
            ->with('vouchers', $vouchers);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('modules.lc.vouchers.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
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
            return Redirect::route('modules.lc.vouchers.create')
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

            return Redirect::route('modules.lc.vouchers.index');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
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
     * @return Response
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
     * @return Response
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
            return Redirect::route('modules.lc.vouchers.edit', ['id' => $id])
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

            return Redirect::route('modules.lc.vouchers.index');
        }
    }
}
