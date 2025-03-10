<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;

class LogController extends Controller
{
    public function insert($type, $table, $by, $rid)
    {
        Log::create([
            'type' => $type,
            'table' => $table,
            'by' => $by,
            'rid' => $rid
        ]);
    }

    public function index(){
        $logs=Log::with(['user'])->orderBy('id','desc')->get();
        return view('admin.log.index',compact('logs'));
    }
}
