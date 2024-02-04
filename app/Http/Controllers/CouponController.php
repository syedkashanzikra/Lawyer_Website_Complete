<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Plan;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::user()->can('manage coupon')) {
            $coupons = Coupon::get();

            return view('coupon.index', compact('coupons'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::user()->can('create coupon')) {
            return view('coupon.create');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Auth::user()->can('create coupon')) {
            $validator = \Validator::make(
                $request->all(), [
                    'name' => 'required',
                    'discount' => 'required|numeric|min:1|max:100',
                    'limit' => 'required|numeric|min:1',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            if (empty($request->manualCode) && empty($request->autoCode)) {
                return redirect()->back()->with('error', 'Coupon code is required');
            }
            $coupon = new Coupon();
            $coupon->name = $request->name;
            $coupon->discount = $request->discount;
            $coupon->limit = $request->limit;

            if (!empty($request->manualCode)) {
                $coupon->code = strtoupper($request->manualCode);
            }

            if (!empty($request->autoCode)) {
                $coupon->code = $request->autoCode;
            }

            $coupon->save();

            return redirect()->route('coupons.index')->with('success', __('Coupon successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    {

        $userCoupons = UserCoupon::where('coupon', $coupon->id)->get();

        return view('coupon.view', compact('userCoupons'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        if (\Auth::user()->can('edit coupon')) {
            return view('coupon.edit', compact('coupon'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon)
    {
        if (\Auth::user()->can('edit coupon')) {
            $validator = \Validator::make(
                $request->all(), [
                    'name' => 'required',
                    'discount' => 'required|numeric|min:1|max:100',
                    'limit' => 'required|numeric|min:1',
                    'code' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $coupon = Coupon::find($coupon->id);
            $coupon->name = $request->name;
            $coupon->discount = $request->discount;
            $coupon->limit = $request->limit;
            $coupon->code = $request->code;

            $coupon->save();

            return redirect()->route('coupons.index')->with('success', __('Coupon successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(coupon $coupon)
    {
        if (\Auth::user()->can('delete coupon')) {
            $coupon->delete();

            return redirect()->route('coupons.index')->with('success', __('Coupon successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function applyCoupon(Request $request)
    {
        $plan_id = decrypt($request->plan_id);
        $plan = Plan::find($plan_id);
        if ($plan && $request->coupon != '') {
            $original_price = self::formatPrice($plan->price);
            $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
            if (!empty($coupons)) {
                $usedCoupun = $coupons->used_coupon();

                if ($coupons->limit == $usedCoupun) {
                    return response()->json(
                        [
                            'is_success' => false,
                            'final_price' => $original_price,
                            'price' => number_format($plan->price, 2),
                            'message' => __('This coupon code has expired.'),
                        ]
                    );
                } else {
                    $discount_value = ($plan->price / 100) * $coupons->discount;
                    $plan_price = $plan->price - $discount_value;
                    $price = self::formatPrice($plan->price - $discount_value);
                    $discount_value = '-' . self::formatPrice($discount_value);

                    return response()->json(
                        [
                            'is_success' => true,
                            'discount_price' => $discount_value,
                            'final_price' => $price,
                            'price' => number_format($plan_price, 2),
                            'message' => __('Coupon code has applied successfully.'),
                        ]
                    );
                }
            } else {
                return response()->json(
                    [
                        'is_success' => false,
                        'final_price' => $original_price,
                        'price' => number_format($plan->price, 2),
                        'message' => __('This coupon code is invalid or has expired.'),
                    ]
                );
            }
        }
    }

    public function formatPrice($price)
    {
        $settings = Utility::settings();
        return $settings['site_currency_symbol'] . number_format($price, 2);
    }
}
