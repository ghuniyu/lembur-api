<?php

namespace App\Http\Controllers\API;

use App\Models\Lembur;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LemburController extends Controller
{


    /**
     * LemburController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function userLembur(Request $request)
    {
        $data = [
            'error' => false,
            'data' => $request->user()->lemburs
        ];
        return response()->json($data,200);
    }

    public function index()
    {
        $this->authorize('accept');
        $data = [
            'error' => false,
            'data' => Lembur::where('status',0)->get()
        ];
        return response()->json($data,200);
    }

    public function all()
    {
        $this->authorize('accept');
        $data = [
            'error' => false,
            'data' => Lembur::all()
        ];
        return response()->json($data,200);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $user = $request->user();
        switch ($user->jabatan){
            case 'manager':
                $data['fee'] = 80000 * $request->jam;
                break;
            case 'staff':
                $data['fee'] = 50000 * $request->jam;
                break;
            case 'cs':
                $data['fee'] = 30000 * $request->jam;
                break;
        }
        $request->user()->lemburs()->create($data);
        $data = [
            'error' => false,
            'user' => $request->user()
        ];
        return response()->json($data,200);
    }


    public function accept($id)
    {
        $this->authorize('accept');

        $lembur = Lembur::findOrFail($id);
        $lembur->status = 1;
        $lembur->update();
        $data = [
            'error' => false,
            'data' => $lembur
        ];
        return response()->json($data,200);
    }

    public function reject($id)
    {
        $this->authorize('accept');

        $lembur = Lembur::findOrFail($id);
        $lembur->delete();
        $data = [
            'error' => false,
            'data' => []
        ];
        return response()->json($data,200);
    }

}
