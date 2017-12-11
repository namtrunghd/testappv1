<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Member;
use DateTime;
use DB,Session;
use App\Http\Requests\MemberAddRequest;
use App\Http\Requests\MemberEditRequest;


class MembersController extends Controller
{


    public function getList(){
        return Member::orderBy('id','DESC')->get();
    }

    public function postAdd(MemberAddRequest $request){
       	$member = new Member;
    	$member->name = $request->name;
    	$member->age = $request->age;
    	$member->address = $request->address;
    	
        if ($request->hasFile('photo')) {
            $img = $request->file('photo')->getClientOriginalName();
            $member->photo = $img;
            $request->photo->move('photo',$img);
        }
    	$member->created_at = new DateTime();  
    	$member->save();
        return response()->json('Add Success');
    }

    public function getEdit($id){
    	return Member::findOrFail($id);
    }

    public function postEdit(MemberEditRequest $request, $id){
    	$member = new Member;
    	$arr['name'] = $request->name;
        $arr['age'] = $request->age;
        $arr['address'] = $request->address;
        if ($request->hasFile('photo')) {
            $img = $request->file('photo')->getClientOriginalName();
            $arr['photo'] = $img;
            $request->photo->move('photo',$img);
        }
        $member::where('id',$id)->update($arr);
    	return response()->json('Edit Success');
    }

    public function getDel($id){
    	$member = Member::findOrFail($id);
        $img = $member['photo'];
        if(file_exists('photo/'.$img)){
        @unlink('photo/'.$img);
    }
    	$member->delete();
    }
}
