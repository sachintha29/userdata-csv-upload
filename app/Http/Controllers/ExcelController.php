<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessExcel;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\UserData;

class ExcelController extends Controller
{
    public function index()
    {
        return view('upload');
    }

    public function store(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:102400' // 100MB max
        ]);

        $path = $request->file('excel_file')->store('excel_imports', 'public');


        // $path = $request->file(key: 'excel_file')->store(path: 'excel_imports');

        // $publicPath = storage_path('app/public/' . $path);

        ProcessExcel::dispatch($path);

        return back()->with('success', 'Excel file is being processed!');
    }

    public function show()
    {
        $users = UserData::paginate(25);
        return view('users', compact('users'));
    }
}
