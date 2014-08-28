<?php namespace App\API\LoyaltyCard\Controllers;
use Validator, Response, Request;
use \App\LoyaltyCard\Models\Voucher as VoucherModel;
use App\Core\Controllers\Base as Base;
use Auth;

class Voucher extends Base {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $vouchers = VoucherModel::where('user_id', Auth::user()->id)->get();

        return Response::json([
            'error' => false,
            'vouchers' => $vouchers->toArray(),
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $inserted_id = 0;
        $rules = [
            'name'          => 'required',
            'required'      => 'required|numeric',
            'value'         => 'required|numeric',
            'type'          => 'required',
            'active'        => 'required|numeric',
        ];

        $validator = Validator::make(Request::all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'error' => true,
                'message' => 'Invalid data'
            ], 400);
        } else {
            $voucher = new VoucherModel;
            $voucher->name = Request::get('name');
            $voucher->user_id = Auth::user()->id;
            $voucher->required = Request::get('required');
            $voucher->value = Request::get('value');
            $voucher->type = Request::get('type');
            $voucher->total_used = 0;
            $voucher->is_active = Request::get('active');
            $voucher->save();
            $inserted_id = $voucher->id;

            return Response::json([
                'error' => false,
                'created' => $inserted_id,
                'message' => 'Voucher created',
            ], 201);
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
        $voucher = VoucherModel::where('user_id', Auth::user()->id)
                        ->where('id', $id)
                        ->take(1)
                        ->get();

        return Response::json([
            'error' => false,
            'vouchers' => $voucher->toArray(),
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $voucher = VoucherModel::where('user_id', Auth::user()->id)->find($id);

        foreach ([
            'name'      => 'name',
            'required'  => 'required',
            'value'     => 'value',
            'type'      => 'type',
            'active'    => 'is_active',
        ] as $key => $value) {
            if (Request::get($key)) {
                $voucher->$value = Request::get($key);
            }
        }

        $voucher->save();

        return Response::json([
            'error' => false,
            'message' => 'Voucher updated',
        ], 201);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $voucher = VoucherModel::where('user_id', Auth::user()->id)->find($id);
        $voucher->delete();

        return Response::json([
            'error' => false,
            'message' => 'Voucher deleted',
        ], 204);
    }
}
