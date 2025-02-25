<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Hostel;
use App\Models\Seat;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Hostel $hostel)
    {
        //return $hostel;
        $rooms = Room::where('hostel_id',$hostel->id)->orderBy('roomno')->get();
        $data = [
            'hostel' => $hostel,
            'rooms' => $rooms
        ];
        return view('common.room.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $seats = Seat::where('room_id',$id)->orderBy('serial')->get();
        $data = [
            'room' => Room::findOrFail($id),
            'seats' => $seats
        ];
        return view('common.room.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function remark($id){
        $room = Room::findOrFail($id);
        return view('common.room.remark',['room' => $room]);
    }

    public function remarkStore($id){
        \App\Models\RoomRemark::create([
            'room_id' => $id,
            'remark_dt' => date('Y-m-d'),
            'remark' => request()->remark
        ]);
        return $id;
    }
}
