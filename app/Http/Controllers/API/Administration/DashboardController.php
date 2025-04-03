<?php

namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Models\Module\Client;
use App\Models\Module\Device;
use App\Models\Module\Operator;
use App\Models\Module\Order;
use App\Models\Module\Product;
use App\Models\Module\SystemUser;
use App\Models\Module\Worker;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private $dates = ["january", "february", "march", "april", "june", "july", "may", "august", "november", "december"];
    public function index()
    {
        $total_orders = Order::count();
        $total_clients = Client::count();
        $total_workers = Worker::count();
        $total_system_users = SystemUser::count();
        $total_operators = Operator::count();
        $total_devices = Device::count();
        $total_products = Product::count();

        $user = User::find(Auth::user()->id);
        $subscription_quantity = $user->company->order_quantity;

        return response()->json(
            compact('total_orders', 'total_clients', 'total_workers', 'total_system_users', 'total_operators', 'total_devices', 'total_products', 'subscription_quantity')
        );

    }

    public function filterDashboard(Request $request)
    {
        $response = [];

        switch ($request->period_filter) {

            case 'daily':
                $response = $this->dailyOrders($request);
                break;

            case 'weekly':
                $response = $this->weeklyOrders($request);
                break;

            case 'biweekly':
                $response = $this->weeklyOrders($request, 14);
                break;

            case 'monthly':
                $response = $this->monthlyOrders($request);
                break;

            case 'biannual':
                $response = $this->monthlyOrders($request, 6);
                break;

            case 'annual':
                $response = $this->anualOrders($request);
                break;

        }
        $highchart = $this->formatHighcharts($response);

        if ($request->period_filter == 'daily') {
            usort($highchart, function ($a, $b) {
                $dateA = new DateTime($a['drilldown']);
                $dateB = new DateTime($b['drilldown']);
                return $dateA <=> $dateB;
            });
        }

        return $highchart;
    }

    private function getEntityAnualData($filter_type, $start, $end)
    {
        $data = null;
        switch ($filter_type) {
            case 'orders':
                $data = Order::whereYear(DB::raw('DATE(date)'), '>=', $start)
                    ->whereYear(DB::raw('DATE(date)'), '<=', $end)
                    ->get();

                break;

            case 'clients':
                $data = Client::select('created_at as date')->whereYear(DB::raw('DATE(created_at)'), '>=', $start)
                    ->whereYear(DB::raw('DATE(created_at)'), '<=', $end)
                    ->get();

                break;

            case 'workers':
                $data = Worker::select('created_at as date')->whereYear(DB::raw('DATE(created_at)'), '>=', $start)
                    ->whereYear(DB::raw('DATE(created_at)'), '<=', $end)
                    ->get();

                break;

            case 'operators':

                $data = User::join('administration.operators as operator', 'users.id', '=', 'operator.administrator_id')
                    ->whereYear(DB::raw('DATE(users.created_at)'), '>=', $start)
                    ->whereYear(DB::raw('users.created_at'), '<=', $end)
                    ->get();

                break;

            case 'system_users':
                $data = User::join('administration.system_users as system_user', 'users.id', '=', 'system_user.administrator_id')
                    ->whereYear(DB::raw('users.created_at'), '>=', $start)
                    ->whereYear(DB::raw('users.created_at'), '<=', $end)
                    ->get();

                break;

        }

        return $data;
    }

    private function getEntityDailyData(Request $request)
    {
        $data = null;
        switch ($request->filter_type) {
            case 'orders':

                $data = Order::whereBetween(DB::raw('DATE(date)'), [$request->date_1, $request->date_2])->get();

                break;

            case 'clients':

                $data = Client::select('created_at as date')->whereBetween(DB::raw('DATE(created_at)'), [$request->date_1, $request->date_2])->get();

                break;

            case 'workers':

                $data = Worker::select('created_at as date')->whereBetween(DB::raw('DATE(created_at)'), [$request->date_1, $request->date_2])->get();

                break;

            case 'operators':

                $data = User::join('administration.operators as operator', 'users.id', '=', 'operator.administrator_id')
                    ->whereBetween(DB::raw('DATE(users.created_at)'), [$request->date_1, $request->date_2])->get();

                break;

            case 'system_users':
                $data = User::join('administration.system_users as system_user', 'users.id', '=', 'system_user.administrator_id')
                    ->whereBetween(DB::raw('DATE(users.created_at)'), [$request->date_1, $request->date_2])->get();
                break;

        }

        return $data;
    }

    private function getEntityMonthlyData($filter_type, $start_date, $end_date)
    {
        $data = null;
        switch ($filter_type) {
            case 'orders':
                $data = Order::whereBetween('date', [$start_date, $end_date])->get();

                break;

            case 'clients':

                $data = Client::select('first_name', 'last_name', 'created_at as date')->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->get();

                break;

            case 'workers':

                $data = Worker::select('created_at as date')->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])->get();

                break;

            case 'operators':

                $data = User::join('administration.operators as operator', 'users.id', '=', 'operator.administrator_id')
                    ->whereBetween(DB::raw('users.created_at'), [$start_date, $end_date])->get();

                break;

            case 'system_users':
                $data = User::join('administration.system_users as system_user', 'users.id', '=', 'system_user.administrator_id')
                    ->whereBetween(DB::raw('users.created_at'), [$start_date, $end_date])->get();
                break;

        }

        return $data;
    }

    private function anualOrders(Request $request)
    {
        $start = $request->annual_1;
        $end = $request->annual_2;

        $data = $this->getEntityAnualData($request->filter_type, $request->annual_1, $request->annual_2);
        $date_range = $this->createAnualArray($start, $end);

        $anual_results = [];
        foreach ($date_range as $key => $value) {
            $i = 0;
            foreach ($data as $o => $order) {
                $order_date = Carbon::parse($order->date)->format('Y');

                if (!array_key_exists($order_date, $anual_results)) {
                    $anual_results[$order_date] = 0;
                }

                if ($order_date == $value) {

                    $anual_results[$order_date] = $anual_results[$order_date] + 1;

                }
            }

        }

        return $anual_results;

    }

    private function dailyOrders(Request $request)
    {
        $data = $this->getEntityDailyData($request);
        $date_range = $this->createWeeklyArray($request->date_1, $request->date_2);
        $daily_orders = [];

        foreach ($data as $key => $value) {
            $order_date = Carbon::parse($value->date)->format('Y-m-d');

            foreach ($date_range as $date) {
                $d = Carbon::parse($date)->format('Y-m-d');

                if ($d == $order_date) {

                    if (!array_key_exists($order_date, $daily_orders)) {
                        $daily_orders[$order_date] = 0;
                    }

                    $daily_orders[$order_date] = $daily_orders[$order_date] + 1;
                    break;

                }
            }
        }

        return $daily_orders;

    }

    private function weeklyOrders(Request $request, $week_pivot = 7)
    {
        $data = $this->getEntityDailyData($request);

        $date_range = $this->createWeeklyArray($request->date_1, $request->date_2);
        $weeks = array_chunk($date_range, $week_pivot);

        $week_results = [];
        foreach ($weeks as $key => $value) {
            $i = 0;
            foreach ($data as $o => $order) {
                $order_date = Carbon::parse($order->date)->format('Y-m-d');

                if (in_array($order_date, $value)) {
                    if (!array_key_exists($value[0] . " a " . end($value), $week_results)) {
                        $week_results[$value[0] . " a " . end($value)] = 0;
                    }

                    $week_results[$value[0] . " a " . end($value)] = $week_results[$value[0] . " a " . end($value)] + 1;

                }
            }

        }

        return $week_results;

    }

    private function monthlyOrders(Request $request, $month_pivot = 1)
    {

        $start_month = $request->month_1;
        $end_month = $request->month_2;
        $year = $request->month_year;

        $months = $this->getMonthsByString($start_month, $end_month);
        if ($month_pivot == 1) {
            $months = [$months];

        } else {
            $months = array_chunk($months, $month_pivot);

        }
        Carbon::setLocale('es');

        $start_date = Carbon::createFromFormat('F Y', "{$start_month} {$year}")->startOfMonth();
        $end_date = Carbon::createFromFormat('F Y', "{$end_month} {$year}")->endOfMonth();

        $data = $this->getEntityMonthlyData($request->filter_type, $start_date, $end_date);

        $month_results = [];

        foreach ($months as $key => $value) {

            foreach ($data as $order) {

                $d = Carbon::parse($order->date)->format('Y-m-d');
                $date = Carbon::createFromFormat('Y-m-d', $d)->isoFormat('MMMM');

                if (!array_key_exists($date, $month_results)) {
                    $month_results[$date] = 0;

                }

                if (in_array($date, $value)) {

                    $month_results[$date] = $month_results[$date] + 1;

                }

            }

        }

        return $month_results;
    }

    private function createWeeklyArray($start_date, $end_date)
    {

        $dates = [];
        $date = Carbon::parse($start_date)->format('Y-m-d');
        while ($date <= $end_date) {
            $dates[] = $date;
            $date = date('Y-m-d', strtotime($date . ' +1 day'));
        }

        return $dates;
    }

    private function createAnualArray($start_year, $end_year)
    {

        $range = range($start_year, $end_year);
        return $range;
    }

    private function getMonthsByString($start_month, $end_month)
    {
        Carbon::setLocale('es');

        $startDate = Carbon::createFromFormat('F', $start_month)->startOfMonth();
        $endDate = Carbon::createFromFormat('F', $end_month)->startOfMonth();

        $months = [];
        while ($startDate->lte($endDate)) {
            $months[] = $startDate->isoFormat('MMMM');
            $startDate->addMonth();
        }

        return $months;
    }

    public function fillMissingMonths($startMonth, $endMonth)
    {
        $months = [];
        $currentMonth = $startMonth;

        while ($currentMonth != $endMonth) {
            $months[] = $currentMonth;
            $currentMonth = ($currentMonth + 1) % 13;

            if ($currentMonth == 0) {
                $currentMonth = 1;
            }
        }

        $months[] = $endMonth;

        return $months;
    }

    private function formatHighcharts($data)
    {
        $response = [];
        foreach ($data as $key => $value) {
            $data = [
                "name" => $key,
                "y" => $value,
                "drilldown" => $key,
            ];

            $response[] = $data;
        }

        return $response;
    }

}
