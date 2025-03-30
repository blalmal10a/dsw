<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    public $guarded = [];

    public function students(){
        return $this->hasMany(Student::class);
    }

    public function person_remarks(){
        return $this->hasMany(PersonRemark::class);
    }

    public function student(){
        return Student::where('person_id',$this->id)->first();
    }

    public function others(){
        return Other::where('person_id',$this->id)->get;
    }
    
    public function other(){
        return Other::where('person_id',$this->id)->first();
    }

    public function allot_hostels(){
        return $this->hasMany(AllotHostel::class);
    }

    public function allotments(){
        return $this->hasMany(Allotment::class);
    }

    public function allot_hostel(){
        return AllotHostel::where('person_id',$this->id)->first();
    }

    public function allotment(){
        return Allotment::where('person_id',$this->id)->first();
    }

    public function valid_allot_hostel(){
        return AllotHostel::where('person_id',$this->id)->where('valid',1)->first();
    }

    public function valid_allotment(){
        return Allotment::where('person_id',$this->id)->where('valid',1)->first();
    }

}
