<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Sessn;
use App\Models\Hostel;
use App\Models\Allotment;
use App\Models\AllotHostel;
use App\Models\AllotSeat;

use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index2(Allotment $allotment){
        return $allotment;
    }
    public function index(Hostel $hostel)
    {
        // return $hostel;
        if(isset($_GET['sessn'])){
            $sessn = Sessn::findOrFail($_GET['sessn']);
        }
        else{
            $sessn = Sessn::current();
        }
        
        $adm_type = isset($_GET['adm_type']) && $_GET['adm_type'] == 'new'?'new':'old';

        $allot_hostels = AllotHostel::where('hostel_id',$hostel->id)->where('valid',1);
        
        if($adm_type == 'old'){
            
            $data = [
                'sessn' => $sessn,
                'allot_hostels' => $allot_hostels->get(),
                'hostel' => $hostel,
                'adm_type' => $adm_type
            ];
            return view("common.admission.index",$data);
        }
        else{
            $new_allotments = Allotment::where('hostel_id',$hostel->id)
                ->where('admitted',0);
            $data = [
                'sessn' => $sessn,
                'new_allotments' => $new_allotments->get(),
                'hostel' => $hostel,
                'adm_type' => $adm_type
            ];
            // return $data;
            return view("common.admission.newadmissionindex",$data);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Allotment $allotment)
    {
        if(isset($_GET['sessn_id'])){
            $sessn = Sessn::findOrFail($_GET['sessn_id']);
        }
        else{
            $sessn = Sessn::default();
        }
        $data = [
            'admissions' => Admission::where('allotment_id',$allotment->id)->get(),
            'allotment' => $allotment,
            'sessn' => $sessn
        ];
        return view('common.admission.create',$data);
        // return $allotment;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,Allotment $allotment)
    {
        if(!$allotment->valid_allot_hostel()){
            $allot_hostel = AllotHostel::create([
                'allotment_id' => $allotment->id,
                'hostel_id' => $allotment->hostel->id,
                'valid' => 1,
                'from_dt' => $allotment->from_dt,
                'to_dt' => $allotment->to_dt,
            ]);
        }
        else{
            $allot_hostel = $allotment->valid_allot_hostel();
        }

        $allot_seat = AllotSeat::updateOrCreate([
            'allot_hostel_id' => $allot_hostel->id,
            'seat_id' => $request->seat,
        ],
        [
            'allot_hostel_id' => $allot_hostel->id,
            'seat_id' => $request->seat,
            'valid' => 1,
            'from_dt' => $allot_hostel->from_dt,
            'to_dt' => $allot_hostel->to_dt,
        ]);

        Admission::updateOrCreate([
            'allotment_id' => $allotment->id,
            'sessn_id' => $request->sessn_id,
            'allot_hostel_id' => $allot_hostel->id,
        ],
        [
            'allotment_id' => $allotment->id,
            'sessn_id' => $request->sessn_id,
            'allot_hostel_id' => $allot_hostel->id,
        ]);

        $allotment->update([
            'admitted' => 1
        ]);

        return redirect('/hostel/' . $allotment->hostel->id . '/admission?sessn=1&adm_type=new')
            ->with(['message' => ['type' => 'info', 'text' => 'Admission done successfully']]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Admission $admission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admission $admission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admission $admission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admission $admission)
    {
        //
    }
}
