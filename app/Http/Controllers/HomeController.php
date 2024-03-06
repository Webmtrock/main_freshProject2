<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\VendorProfile;
use Carbon\Carbon;
use DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function dashboard() 
    {   
        if(Auth::user()->roles->contains('5')){
            return redirect()->route('admin.users.index');
        }
        $data['total_orders'] = Order::selectRaw('COUNT(*) as total_orders')->first();
        $data['total_customers'] = User::whereHas('roles', function($q){ $q->where('name', 'Customer');})->selectRaw('COUNT(*) as total_customers')->first();
        $data['total_stores'] = User::whereHas('roles', function($q){ $q->where('name', 'Store');})->selectRaw('COUNT(*) as total_stores')->first();
        $data['completed_orders'] = Order::selectRaw('COUNT(*) as total_orders_completed')->where('status', 'D')->first();
        $data['recent_orders'] = Order::orderBy('created_at', 'DESC')->take(5)->get();
        $data['recent_stores'] = VendorProfile::where('status', 1)->orderBy('created_at', 'DESC')->take(5)->get();
        $data['top_stores'] = Order::select('vendor_id')->selectRaw('COUNT(*) as total_orders')->groupBy('vendor_id')->orderBy('total_orders', 'DESC')->take(5)->get();

        $category_data = OrderDetail::join('products', 'products.id', 'order_details.product_id')->join('categories', 'categories.id', 'products.category_id')->select('product_id', 'categories.id as category_id', 'categories.image', 'categories.name')->where('order_details.status', 'A')->selectRaw('COUNT(*) as total_orders')->groupBy('product_id', 'categories.id', 'categories.image', 'categories.name')->orderBy('total_orders', 'DESC')->take(3)->get();
        $temp_data = [];
        $temp_total_orders = [];
        foreach ($category_data as $value) {
            if(array_key_exists($value['category_id'], $temp_data)) {
                $temp_total_orders[$value['category_id']] += $value['total_orders'];
                $temp_data[$value['category_id']]['total_orders'] = $temp_total_orders[$value['category_id']];
            }
            else {
                $temp_total_orders[$value['category_id']] = $value['total_orders'];
                $temp_data[$value['category_id']] = [
                    'category_name' => $value['name'],
                    'category_image' => $value['image'],
                    'total_orders' => $value['total_orders'],
                ];
            }
        }
        $data['top_categories'] = $temp_data;
        
        $yearly_data =  Order::select(DB::raw('month(created_at) as month'))->selectRaw('COUNT(*) as total_orders')->selectRaw('SUM(`grand_total`) as total_earning')->whereYear('created_at', Carbon::now()->year)->groupBy(DB::raw('month(created_at)'))->where('status', 'D')->get();
        $months = ['0','0','0','0','0','0','0','0','0','0','0','0'];
        $total_amount = 0;
        foreach ($months as $month_key => $month) {
            $mKey = $month_key+1;
            foreach ($yearly_data as $year_data) {
                if($mKey == $year_data->month){
                    $months[$month_key] = (string)$year_data->total_orders;
                    $total_amount += $year_data->total_earning;
                }
            }
        }
        $data['yearly_data'] = implode('","',$months);
        $data['yearly_total_earning'] = $total_amount;
        $this_week_datas = Order::select(DB::raw('weekday(created_at) as weekday'))->selectRaw('COUNT(*) as total_orders')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->groupBy(DB::raw('weekday(created_at)'))->where('status', 'D')->get();
        $this_weeks = ['0','0','0','0','0','0','0'];
        foreach ($this_weeks as $this_week_key => $this_week) {
            foreach ($this_week_datas as $this_week_data) {
                if($this_week_key == $this_week_data->weekday){
                    $this_weeks[$this_week_key] = (string)$this_week_data->total_orders;
                } 
            }
        }
        $data['this_week_data'] = implode('","',$this_weeks);
        $last_week_datas = Order::select(DB::raw('weekday(created_at) as weekday'))->selectRaw('COUNT(*) as total_orders')->whereBetween('created_at', [Carbon::now()->startOfWeek()->subWeek(), Carbon::now()->endOfWeek()->subWeek()])->groupBy(DB::raw('weekday(created_at)'))->where('status', 'D')->get();
        $last_weeks = ['0','0','0','0','0','0','0'];
        foreach ($last_weeks as $last_week_key => $last_week) {
            foreach ($last_week_datas as $last_week_data) {
                if($last_week_key == $last_week_data->weekday){
                    $last_weeks[$last_week_key] = (string)$last_week_data->total_orders;
                } 
            }
        }
        $data['last_week_data'] = implode('","',$last_weeks);
        return view('admin.index', $data);
    }
}
