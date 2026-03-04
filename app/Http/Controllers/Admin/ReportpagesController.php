<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payments;
use Illuminate\Http\Request;

class ReportpagesController extends Controller
{
    
    public function index(){
        $total   = Payments::sum('amount');
        $paid    = Payments::where('status', 'จ่าย')->sum('amount');
        $pending = Payments::where('status', 'ไม่จ่าย')->sum('amount');
        $items   = Payments::orderBy('created_at', 'desc')->get();

        return view('admin.report', compact('total', 'paid', 'pending', 'items'));
    }
    
}