<?php

namespace App\Http\Controllers;

use App\Models\PatientReference;
use App\Models\PatientRegistrations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Pharmacy;
use App\Models\Product;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PharmacyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $branch;

    function __construct()
    {
        $this->middleware('permission:pharmacy-list|pharmacy-create|pharmacy-edit|pharmacy-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:pharmacy-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:pharmacy-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:pharmacy-delete', ['only' => ['destroy']]);
        $this->branch = session()->get('branch');
    }
    public function index()
    {
        $records = DB::table('pharmacy_records as pr')->leftJoin('pharmacies as p', 'pr.pharmacy_id', '=', 'p.id')->whereNotIn('p.used_for', ['B2B'])->where('p.branch', $this->branch)->whereDate('p.created_at', Carbon::today())->whereNull('p.stock_updated_at')->select('p.id', 'p.patient_name', 'p.other_info', 'p.used_for', DB::raw("DATE_FORMAT(p.created_at, '%d/%b/%Y') AS cdate"))->groupBy('p.id')->orderByDesc('p.id')->get();
        return view('pharmacy.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = DB::table('products')->get();
        $pref = null;
        $patient = null;
        return view('pharmacy.create', compact('products', 'pref', 'patient'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'patient_name' => 'required',
            'used_for' => 'required',
        ]);
        $input = $request->all();
        $input['created_by'] = Auth::user()->id;
        $input['updated_by'] = Auth::user()->id;
        $input['branch'] = $this->branch;
        try {
            $pharmacy = Pharmacy::create($input);
            if (!empty($input['product'])):
                for ($i = 0; $i < count($request->product); $i++):
                    $product = DB::table('products')->where('id', $request->product[$i])->first();
                    $data[] = [
                        'pharmacy_id'   => $pharmacy->id,
                        'product'       => $request->product[$i],
                        'category'      => $product->category_id,
                        'type'          => $product->medicine_type,
                        'batch_number'  => $request->batch_number[$i],
                        'qty'           => $request->qty[$i],
                        'dosage'        => $request->dosage[$i],
                        'duration'      => $request->duration[$i],
                        'price'         => $request->price[$i],
                        'mrp'         => $request->mrp[$i],
                        'discount'      => $request->discount[$i],
                        'tax'           => $request->tax[$i],
                        'tax_amount'    => $request->tax_amount[$i],
                        'total'         => $request->total[$i],
                    ];
                endfor;
                DB::table('pharmacy_records')->insert($data);
            endif;
        } catch (Exception $e) {
            throw $e;
        }
        return redirect()->route('pharmacy.index')
            ->with('success', 'Record added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $this->validate($request, [
            'medical_record_id' => 'required',
        ]);
        $pref = PatientReference::findOrFail($request->medical_record_id);
        if ($pref->exists()):
            $patient = PatientRegistrations::find($pref->patient_id);
            $products = DB::table('products')->get();
            return view('pharmacy.create', compact('products', 'pref', 'patient'));
        else:
            return redirect()->back()->with('error', 'No records found.');
        endif;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $products = DB::table('products')->get();
        $pharmacy = Pharmacy::find($id);
        $records = DB::table('pharmacy_records')->where('pharmacy_id', $id)->get();
        return view('pharmacy.edit', compact('products', 'pharmacy', 'records'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'patient_name' => 'required',
        ]);
        $input = $request->all();
        $pharmacy = Pharmacy::find($id);
        $input['updated_by'] = Auth::user()->id;
        $input['created_by'] = $pharmacy->getOriginal('created_by');
        $input['branch'] = $this->branch;
        try {
            $pharmacy->update($input);
            DB::table("pharmacy_records")->where('pharmacy_id', $id)->delete();
            if (!empty($input['product'])):
                for ($i = 0; $i < count($request->product); $i++):
                    $product = DB::table('products')->where('id', $request->product[$i])->first();
                    $data[] = [
                        'pharmacy_id'   => $pharmacy->id,
                        'product'       => $request->product[$i],
                        'category'      => $product->category_id,
                        'type'          => $product->medicine_type,
                        'batch_number'  => $request->batch_number[$i],
                        'qty'           => $request->qty[$i],
                        'dosage'        => $request->dosage[$i],
                        'duration'        => $request->duration[$i],
                        'price'         => $request->price[$i],
                        'mrp'         => $request->mrp[$i],
                        'discount'      => $request->discount[$i],
                        'tax'           => $request->tax[$i],
                        'tax_amount'    => $request->tax_amount[$i],
                        'total'         => $request->total[$i],
                    ];
                endfor;
                DB::table('pharmacy_records')->insert($data);
            endif;
        } catch (Exception $e) {
            throw $e;
        }
        return redirect()->route('pharmacy.index')
            ->with('success', 'Record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Pharmacy::find($id)->delete();
        return redirect()->route('pharmacy.index')
            ->with('success', 'Record deleted successfully');
    }

    public function b2bindex()
    {
        $records = Pharmacy::where('used_for', 'B2B')->latest()->get();
        return view('pharmacy.b2b.index', compact('records'));
    }

    public function b2bcreate()
    {
        $products = Product::orderBy('product_name')->get();
        return view('pharmacy.b2b.create', compact('products'));
    }

    public function b2bstore(Request $request)
    {
        $this->validate($request, [
            'patient_name' => 'required',
            'used_for' => 'required',
            'other_info' => 'required',
            'contact' => 'required',
        ]);
        try {
            DB::transaction(function () use ($request) {
                $pharmacy = Pharmacy::create([
                    'patient_name' => $request->patient_name,
                    'other_info' => $request->other_info,
                    'branch' => $this->branch,
                    'used_for' => 'B2B',
                    'contact' => $request->contact,
                    'gstin' => $request->gstin,
                    'addition' => $request->addition,
                    'created_by' => $request->user()->id,
                    'updated_by' => $request->user()->id,
                ]);
                $data = [];
                foreach ($request->product as $key => $item):
                    $product = Product::find($item);
                    $data[] = [
                        'pharmacy_id'   => $pharmacy->id,
                        'product'       => $request->product[$key],
                        'category'      => $product->category_id,
                        'type'          => $product->medicine_type,
                        'batch_number'  => $request->batch_number[$key],
                        'qty'           => $request->qty[$key],
                        'price'         => $request->price[$key],
                        'mrp'         => $request->mrp[$key],
                        'discount'      => $request->discount[$key],
                        'tax'           => $request->tax[$key],
                        'tax_amount'    => $request->tax_amount[$key],
                        'total'         => $request->total[$key],
                    ];
                endforeach;
                DB::table('pharmacy_records')->insert($data);
            });
        } catch (Exception $e) {
            throw $e;
        }
        return redirect()->route('pharmacy.b2b.index')
            ->with('success', 'Record added successfully');
    }

    public function b2bedit(string $id)
    {
        $pharmacy = Pharmacy::findOrFail(decrypt($id));
        $products = Product::orderBy('product_name')->get();
        $records = DB::table('pharmacy_records')->where('pharmacy_id', $pharmacy->id)->get();
        return view('pharmacy.b2b.edit', compact('products', 'pharmacy', 'records'));
    }

    public function b2bupdate(Request $request, string $id)
    {
        $this->validate($request, [
            'patient_name' => 'required',
            'used_for' => 'required',
            'other_info' => 'required',
        ]);
        dd($request);
        die;
        try {
            DB::transaction(function () use ($request, $id) {
                $pharmacy = Pharmacy::findOrFail(decrypt($id));
                $pharmacy->update([
                    'patient_name' => $request->patient_name,
                    'other_info' => $request->other_info,
                    'contact' => $request->contact,
                    'gstin' => $request->gstin,
                    'addition' => $request->addition,
                    'updated_by' => $request->user()->id,
                ]);
                $data = [];
                foreach ($request->product as $key => $item):
                    $product = Product::find($item);
                    $data[] = [
                        'pharmacy_id'   => $pharmacy->id,
                        'product'       => $request->product[$key],
                        'category'      => $product->category_id,
                        'type'          => $product->medicine_type,
                        'batch_number'  => $request->batch_number[$key],
                        'qty'           => $request->qty[$key],
                        'price'         => $request->price[$key],
                        'mrp'         => $request->mrp[$key],
                        'discount'      => $request->discount[$key],
                        'tax'           => $request->tax[$key],
                        'tax_amount'    => $request->tax_amount[$key],
                        'total'         => $request->total[$key],
                    ];
                endforeach;
                DB::table('pharmacy_records')->where('pharmacy_id', $pharmacy->id)->delete();
                DB::table('pharmacy_records')->insert($data);
            });
        } catch (Exception $e) {
            throw $e;
        }
        return redirect()->route('pharmacy.b2b.index')
            ->with('success', 'Record added successfully');
    }

    public function b2bdelete(string $id)
    {
        Pharmacy::find(decrypt($id))->delete();
        return redirect()->route('pharmacy.b2b.index')
            ->with('success', 'Record deleted successfully');
    }
}
