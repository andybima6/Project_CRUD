<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use PDF;

class EmployeeController extends Controller
{
    public function index(Request $request)
{
    $data = Employee::paginate(5);

    if ($request->has('search')) {
        $data = Employee::where('nama', 'LIKE', '%' . $request->search . '%')->paginate(5);
    }

    return view('datapegawai', compact('data'));
}

    public function tambahpegawai()
    {
        return view('tambahpegawai');
    }
    public function insertpegawai(Request $request)
    {
        // dd($request->all());
        $data = Employee::create($request->all());
        if ($request->hasFile('foto')) {
            $request->file('foto')->move('fotopegawai/', $request->file('foto')->getClientOriginalName());
            $data->foto = $request->file('foto')->getClientOriginalName();
            $data->save();
        }
        return redirect()->route('pegawai')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function tampilkandata($id)
    {
        $data = Employee::find($id);
        // dd($data);
        return view('tampildatapegawai', compact('data'));
    }
    public function updatedata(Request $request, $id)
    {
        $data = Employee::find($id);
        $data->update($request->all());
        return redirect()->route('pegawai')->with('success', 'Data Berhasil Di Update');
    }
    public function deletedata($id)
    {
        $data = Employee::find($id);
        $data->delete();
        return redirect()->route('pegawai')->with('success', 'Data Berhasil Di Delete');
    }
    public function exportpdf(){
        $data = Employee::all();

        view()->share('data',$data);
        $pdf = PDF::loadview('datapegawai-pdf');
        return $pdf->download('data.pdf');
    }
}
