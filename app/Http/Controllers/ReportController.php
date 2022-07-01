<?php

namespace App\Http\Controllers;

use App\Actions\MonthlyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use  App\Mail\bulkMail;
use App\Clientmeeting;
use App\Outcome;
use App\Category;
use App\Clientap;
use App\CurrentNoc;
use App\Client;
use App\User;
use Carbon\Carbon;
class ReportController extends Controller
{
    public function getMonthlyReport(Request $request, MonthlyReport $monthlyReport)
    {
        //       $results = $monthlyReport->execute($request->startYearMonthly, $request->startMonthMonthly, $request->endYearMonthly, $request->endMonthMonthly);
        $results = $monthlyReport->execute($request->start, $request->end);

        return $results;
    }

    public function getFeedbackReport(Request $request)
    {

        $results = Clientmeeting::with('client', 'staff')
            // ->whereBetween('date', ['2019-01-01','2022-06-10'])
            ->whereBetween('date', [$request->startDate, $request->endDate])
            ->paginate(50);

        return $results;
    }
    public function getcategories()
    {
        $cat = Category::get(['id', 'name']);
        return $cat;
    }
    public function outcomesReport(Request $request)
    {
        $category_name = $request->category;
        $from = $request->startDate;
        $to = $request->endDate;
        if ($category_name == 'All') {
            $outcomes = Outcome::whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)->paginate(40);
            return $outcomes;
        }
        if ($category_name != "All") {
            $outcomes = Outcome::whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)->where('category_id', $category_name)->paginate(40);
            return $outcomes;
        }
    }
    public function current_noc(Request $request)
    {
        $current_noc = CurrentNoc::get(['id', 'code', 'title']);
        return $current_noc;
    }
    public function nocReport(Request $request)
    {
        $from = $request->startDate;
        $to = $request->endDate;
        $cilent = Clientap::with('client', 'staff')->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)->where('current_noc', $request->current_noc)->paginate(50);
        return $cilent;
    }
    public function sendBulkMail(Request $request)
    {
        $cilentEmail = array();
        $email = array();
        $cilent = Client::with('usercilent')->whereIn('id', $request->id)->get();
        foreach ($cilent as $cid) {
            $cilentEmail[] = $cid['usercilent'][0];
        }
        foreach ($cilentEmail as $address) {
            Mail::to($address['email'])->send(new bulkMail($request->mail));
        }
        return "Mail Sent";
    }
    public function yearlyReport(Request $request)
    {   
        $start_date='';
        $end_date='';
        $current_year = $request->year;
        $pervious_year =$request->year-1;
        $dateTime = Carbon::now();
        $day = $dateTime->format('d');
        $month = $dateTime->format('m');
        $date = $day.'-'.$month.'-'.$current_year;
        $per_date = $day.'-'.$month.'-'.$pervious_year;
        // $lesdate ='27-03-'.$current_year;
        $less=strtotime($date);
        $check_date ='01-04-'.$current_year;
        $check =strtotime($check_date);
        if($less>$check)
        {
            $start_date.='01-04-'.$current_year;
            $end_date .=$date;
        }
        else
        {
            $start_date.='01-04-'.$pervious_year;
            $end_date .=$per_date;
        }
        
       return $end_date;
    }
}
