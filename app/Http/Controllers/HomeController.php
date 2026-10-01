<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\Returns;
use App\ReturnPurchase;
use App\Purchase;
use App\Expense;
use App\Payroll;
use App\Quotation;
use App\Payment;
use App\Account;
use App\Product_Sale;
use DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        return view('home');
    }

    public function index()
    {
        setcookie('language', 'en', time() + (86400 * 365), "/");
        // Get the first day of the current month
        $start_date = date('Y-m-01');

        // Get the last day of the current month
        $end_date = date('Y-m-t'); // "t" gives the last day number of the month
        $yearly_sale_amount = [];

        $general_setting = DB::table('general_settings')->latest()->first();
        if (Auth::user()->role_id > 2 && $general_setting->staff_access == 'own') {
            // $revenue = Sale::whereDate('created_at', '>=' , $start_date)->where('user_id', Auth::id())->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            $revenue = Sale::whereDate('created_at', '>=', $start_date)
                ->where('user_id', Auth::id())
                ->whereDate('created_at', '<=', $end_date)
                ->where('payment_status', '=', 4)
                ->sum('grand_total');
            // $total_revenue = Sale::select(DB::raw('SUM(CASE WHEN cost_delevery < 0 THEN grand_total + cost_delevery ELSE grand_total END) as total_revenue'))
            //     ->whereDate('created_at', '>=', $start_date)
            //     ->where('user_id', Auth::id())
            //     ->whereDate('created_at', '<=', $end_date)
            //     ->first();

            // $revenue = $total_revenue->total_revenue ?? 0;
            $return = Returns::whereDate('created_at', '>=', $start_date)->where('user_id', Auth::id())->whereDate('created_at', '<=', $end_date)->sum('cost_delevery');
            // $purchase_return = ReturnPurchase::whereDate('created_at', '>=' , $start_date)->where('user_id', Auth::id())->whereDate('created_at', '<=' , $end_date)->sum('grand_total');
            // $revenue = $revenue - $return;
            $purchase = Purchase::whereDate('created_at', '>=', $start_date)->where('user_id', Auth::id())->whereDate('created_at', '<=', $end_date)->sum('grand_total');
            // $profit = $revenue + $purchase_return - $purchase;
            $expense = Expense::whereDate('created_at', '>=', $start_date)->where('user_id', Auth::id())->whereDate('created_at', '<=', $end_date)->where('expense_category_id', '<', 7)->sum('amount');
            $boost = Expense::whereDate('created_at', '>=', $start_date)->where('user_id', Auth::id())->whereDate('created_at', '<=', $end_date)->where('expense_category_id', '>=', 7)->sum('amount');
            $paid = Sale::whereDate('sales.created_at', '>=', $start_date)->where('user_id', Auth::id())->whereDate('sales.created_at', '<=', $end_date)
                ->sum('paid_amount');
            $free_return_cost_item = Sale::whereDate('sales.created_at', '>=', $start_date)->where('user_id', Auth::id())->whereDate('sales.created_at', '<=', $end_date)->whereIn('sale_status', [3, 4])
                ->sum('grand_total');
            $cost_item = Sale::whereDate('sales.created_at', '>=', $start_date)->where('user_id', Auth::id())->whereDate('sales.created_at', '<=', $end_date)
                // ->whereNotIn('sales.sale_status', [3, 4])
                ->join('product_sales', 'product_sales.sale_id', '=', 'sales.id')
                ->join('products', 'products.id', '=', 'product_sales.product_id')
                ->sum('products.cost');
            $free_return_cost_item = $free_return_cost_item * -1;
            $cost_item = $cost_item - $free_return_cost_item;
            // $free_return_cost_item = Sale::whereDate('sales.created_at', '>=' , $start_date)->where('user_id', Auth::id())->whereDate('sales.created_at', '<=' , $end_date)->whereIn('sale_status', [3, 4])
            //             ->join('product_sales','product_sales.sale_id','=','sales.id')
            //             ->join('products','products.id','=','product_sales.product_id')
            //             ->sum('products.cost');
            $recent_sale = Sale::orderBy('id', 'desc')->where('user_id', Auth::id())->take(5)->get();
            $recent_purchase = Purchase::orderBy('id', 'desc')->where('user_id', Auth::id())->take(5)->get();
            $recent_quotation = Quotation::orderBy('id', 'desc')->where('user_id', Auth::id())->take(5)->get();
            $recent_payment = Payment::orderBy('id', 'desc')->where('user_id', Auth::id())->take(5)->get();
            // $profit = $revenue - ( $paid + $expense + $boost +  $cost_item + $free_return_cost_item + $purchase);
            $profit = ($paid - $expense - $boost - $cost_item - $free_return_cost_item);
            $cash = $paid - $purchase - $expense - $boost;
        } else {
            $revenue = Sale::whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->where('payment_status', '=', 4)
                ->sum('grand_total');
            $return_cost = Returns::whereDate('returns.created_at', '>=', $start_date)->whereDate('returns.created_at', '<=', $end_date)
                ->join('product_sales', 'returns.sale_id', '=', 'product_sales.sale_id')
                ->join('products', 'products.id', '=', 'product_sales.product_id')
                ->sum('products.cost');
            $return = $return_cost;
            // $revenue = $revenue - $return;
            $expense = Expense::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->sum('amount');
            $recent_sale = Sale::orderBy('id', 'desc')->take(5)->get();
            $recent_purchase = Purchase::orderBy('id', 'desc')->take(5)->get();
            $recent_quotation = Quotation::orderBy('id', 'desc')->take(5)->get();
            $recent_payment = Payment::orderBy('id', 'desc')->take(5)->get();
            $profit = $revenue - $expense;
            $cost_item = Sale::whereDate('sales.created_at', '>=', $start_date)->where('user_id', Auth::id())->whereDate('sales.created_at', '<=', $end_date)
                // ->whereNotIn('sales.sale_status', [3, 4])
                ->join('product_sales', 'product_sales.sale_id', '=', 'sales.id')
                ->join('products', 'products.id', '=', 'product_sales.product_id')
                ->sum(DB::raw('products.cost * product_sales.qty'));
        }

        $best_selling_qty = Product_Sale::select(DB::raw('product_id, sum(qty) as sold_qty'))->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->groupBy('product_id')->orderBy('sold_qty', 'desc')->take(5)->get();

        $yearly_best_selling_qty = Product_Sale::select(DB::raw('product_id, sum(qty) as sold_qty'))->whereDate('created_at', '>=', date("Y") . '-01-01')->whereDate('created_at', '<=', date("Y") . '-12-31')->groupBy('product_id')->orderBy('sold_qty', 'desc')->take(5)->get();

        $yearly_best_selling_price = Product_Sale::select(DB::raw('product_id, sum(total) as total_price'))->whereDate('created_at', '>=', date("Y") . '-01-01')->whereDate('created_at', '<=', date("Y") . '-12-31')->groupBy('product_id')->orderBy('total_price', 'desc')->take(5)->get();

        //cash flow of last 6 months
        $start = strtotime(date('Y-m-01', strtotime('-6 month', strtotime(date('Y-m-d')))));
        $end = strtotime(date('Y-m-31'));

        while ($start < $end) {
            $start_date = date("Y-m", $start) . '-' . '01';
            $end_date = date("Y-m", $end) . '-' . '31';

            if (Auth::user()->role_id > 2 && $general_setting->staff_access == 'own') {
                $recieved_amount = DB::table('payments')->whereNotNull('sale_id')->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->where('user_id', Auth::id())->sum('amount');
                $sent_amount = DB::table('payments')->whereNotNull('purchase_id')->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->where('user_id', Auth::id())->sum('amount');
                $return_amount = Returns::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->where('user_id', Auth::id())->sum('grand_total');
                $purchase_return_amount = ReturnPurchase::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->where('user_id', Auth::id())->sum('grand_total');
                $expense_amount = Expense::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->where('user_id', Auth::id())->sum('amount');
                $boost_amount = Expense::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->where('user_id', Auth::id())->where('expense_category_id', '>=', 7)->sum('amount');
                $payroll_amount = Payroll::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->where('user_id', Auth::id())->sum('amount');
            } else {
                $recieved_amount = DB::table('payments')->whereNotNull('sale_id')->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->sum('amount');
                $sent_amount = DB::table('payments')->whereNotNull('purchase_id')->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->sum('amount');
                $return_cost_delevery = Returns::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->sum('cost_delevery');
                $return_cost = Returns::whereDate('returns.created_at', '>=', $start_date)->whereDate('returns.created_at', '<=', $end_date)
                    ->join('product_sales', 'returns.sale_id', '=', 'product_sales.sale_id')
                    ->join('products', 'products.id', '=', 'product_sales.product_id')
                    ->sum('products.cost');
                $return_amount = $return_cost_delevery + $return_cost;
                $purchase_return_amount = ReturnPurchase::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->sum('grand_total');
                $expense_amount = Expense::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->sum('amount');
                $boost_amount = Expense::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->where('expense_category_id', '>=', 7)->sum('amount');
                $payroll_amount = Payroll::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->sum('amount');
            }
            $sent_amount = $sent_amount + $return_amount + $expense_amount + $payroll_amount;

            $payment_recieved[] = number_format((float)($recieved_amount + $purchase_return_amount), 2, '.', '');
            $payment_sent[] = number_format((float)$sent_amount, 2, '.', '');
            $month[] = date("F", strtotime($start_date));
            $start = strtotime("+1 month", $start);
        }
        // yearly report
        $start = strtotime(date("Y") . '-01-01');
        $end = strtotime(date("Y") . '-12-31');
        while ($start < $end) {
            $start_date = date("Y") . '-' . date('m', $start) . '-' . '01';
            $end_date = date("Y") . '-' . date('m', $start) . '-' . '31';
            if (Auth::user()->role_id > 2 && $general_setting->staff_access == 'own') {
                $sale_amount = Sale::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->where('user_id', Auth::id())->sum('grand_total');
                $purchase_amount = Purchase::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->where('user_id', Auth::id())->sum('grand_total');
            } else {
                $sale_amount = Sale::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->sum('grand_total');
                $purchase_amount = Purchase::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->sum('grand_total');
            }
            $yearly_sale_amount[] = number_format((float)$sale_amount, 2, '.', '');
            $yearly_purchase_amount[] = number_format((float)$purchase_amount, 2, '.', '');
            $start = strtotime("+1 month", $start);
        }
        //return $month;
        if (Auth::user()->role_id == 1)
            return view('index', compact('revenue', 'cost_item', 'expense', 'return', 'profit', 'payment_recieved', 'payment_sent', 'month', 'yearly_sale_amount', 'yearly_purchase_amount', 'recent_sale', 'recent_purchase', 'recent_quotation', 'recent_payment', 'best_selling_qty', 'yearly_best_selling_qty', 'yearly_best_selling_price'));
        else
            return redirect("/sales");
    }

    public function dashboardFilter($start_date = null, $end_date = null, Request $request)
    {
        if (!$start_date) {
            $start_date = now()->subYear();
        }
        if (!$end_date) {
            $end_date = now();
        }
        $warehouse_id = $request->warehouse_id !== "0" ? $request->warehouse_id : null;
        // dd($warehouse_id);
        $general_setting = DB::table('general_settings')->latest()->first();
        if (Auth::user()->role_id > 2 && $general_setting->staff_access == 'own') {
            $revenue = Sale::whereDate('created_at', '>=', $start_date)
                ->where('user_id', Auth::id())
                ->whereDate('created_at', '<=', $end_date)
                ->where('payment_status', '=', 4)
                // ->where(function($q) use ($warehouse_id) {
                //     if($warehouse_id !== null){
                //     $q->where('warehouse_id', $warehouse_id);
                // }
                // })
                ->sum('grand_total');
            // $revenue = Sale::whereDate('created_at', '>=' , $start_date)->whereDate('created_at', '<=' , $end_date)->where('user_id', Auth::id())->sum('grand_total');
            // $total_revenue = Sale::select(DB::raw('SUM(CASE WHEN cost_delevery < 0 THEN grand_total + cost_delevery ELSE grand_total END) as total_revenue'))
            //     ->whereDate('created_at', '>=', $start_date)
            //     ->where('user_id', Auth::id())
            //     ->whereDate('created_at', '<=', $end_date)
            //     ->first();

            // $revenue = $total_revenue->total_revenue ?? 0;
            $return = Returns::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->where('user_id', Auth::id())
                ->where(function ($q) use ($warehouse_id) {
                    if ($warehouse_id !== null) {
                        $q->where('warehouse_id', $warehouse_id);
                    }
                })
                ->sum('grand_total');
            // $revenue -= $return;
            $expense = Expense::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->where('user_id', Auth::id())
                // ->where(function($q) use ($warehouse_id) {
                //     if($warehouse_id !== null){
                //         $q->where('warehouse_id', $warehouse_id);
                //     }
                // })
                ->sum('amount');
            $profit = $revenue - $expense;

            $data[0] = $revenue;
            $data[1] = $return;
            $data[2] = $profit;
            // $data[3] = $purchase_return;
            // $data[9] = $paid;
            // $data[10] = $cash;
        } else {
            $revenue = Sale::whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)
                ->where('payment_status', '=', 4)
                // ->where(function($q) use ($warehouse_id) {
                //     if($warehouse_id !== null){
                //     $q->where('warehouse_id', $warehouse_id);
                // }
                // })
                ->sum('grand_total');
            // $return = Returns::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)
            //     ->where(function ($q) use ($warehouse_id) {
            //         if ($warehouse_id !== null) {
            //             $q->where('warehouse_id', $warehouse_id);
            //         }
            //     })
            //     ->sum('grand_total');
            $return = Returns::whereDate('returns.created_at', '>=', $start_date)->whereDate('returns.created_at', '<=', $end_date)
                ->join('product_sales', 'returns.sale_id', '=', 'product_sales.sale_id')
                ->join('products', 'products.id', '=', 'product_sales.product_id')
                ->sum('products.cost');
            // $revenue -= $return;
            $expense = Expense::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)
                //             ->where(function($q) use ($warehouse_id) {
                // if($warehouse_id !== null){
                //                     $q->where('warehouse_id', $warehouse_id);
                //                 }
                //             })
                ->sum('amount');
            $profit = $revenue - $expense;
            $cost_item = Sale::whereDate('sales.created_at', '>=', $start_date)->where('user_id', Auth::id())->whereDate('sales.created_at', '<=', $end_date)
                // ->whereNotIn('sales.sale_status', [3, 4])
                ->join('product_sales', 'product_sales.sale_id', '=', 'sales.id')
                ->join('products', 'products.id', '=', 'product_sales.product_id')
                ->sum(DB::raw('products.cost * product_sales.qty'));

            $data[0] = $revenue;
            $data[1] = $return;
            $data[2] = $profit;
            // $data[3] = $purchase_return;
            $data[4] = $expense;
            // $data[5] = $boost;
            $data[6] = $cost_item;
            // $data[7] = $free_return_cost_item;
            // $data[8] = $purchase;
            // $data[9] = $paid;
            // $data[10] = $cash;
        }

        return $data;
    }
    public function myTransaction($year, $month)
    {
        $start = 1;
        $number_of_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        while ($start <= $number_of_day) {
            if ($start < 10)
                $date = $year . '-' . $month . '-0' . $start;
            else
                $date = $year . '-' . $month . '-' . $start;
            $sale_generated[$start] = Sale::whereDate('created_at', $date)->where('user_id', Auth::id())->count();
            $sale_grand_total[$start] = Sale::whereDate('created_at', $date)->where('user_id', Auth::id())->sum('grand_total');
            $purchase_generated[$start] = Purchase::whereDate('created_at', $date)->where('user_id', Auth::id())->count();
            $purchase_grand_total[$start] = Purchase::whereDate('created_at', $date)->where('user_id', Auth::id())->sum('grand_total');
            $quotation_generated[$start] = Quotation::whereDate('created_at', $date)->where('user_id', Auth::id())->count();
            $quotation_grand_total[$start] = Quotation::whereDate('created_at', $date)->where('user_id', Auth::id())->sum('grand_total');
            $start++;
        }
        $start_day = date('w', strtotime($year . '-' . $month . '-01')) + 1;
        $prev_year = date('Y', strtotime('-1 month', strtotime($year . '-' . $month . '-01')));
        $prev_month = date('m', strtotime('-1 month', strtotime($year . '-' . $month . '-01')));
        $next_year = date('Y', strtotime('+1 month', strtotime($year . '-' . $month . '-01')));
        $next_month = date('m', strtotime('+1 month', strtotime($year . '-' . $month . '-01')));
        return view('user.my_transaction', compact('start_day', 'year', 'month', 'number_of_day', 'prev_year', 'prev_month', 'next_year', 'next_month', 'sale_generated', 'sale_grand_total', 'purchase_generated', 'purchase_grand_total', 'quotation_generated', 'quotation_grand_total'));
    }
}
