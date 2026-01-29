<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Hash;

class StudentController extends Controller
{
    public function index()
    {
        $students = User::all();
        //dd( $students );
         return view('students.index',compact('students'));

    }

    public function create()
    {
       return view('students.create');
    }

    public function store(Request $request)
    {
       $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'address' => 'required',
            'phone_number' => 'required'
            
        ]);

       /*  DB::table('users')->insert([
            'name' => $request->name,
            'password' => $request->password,
            'email' => $request->email,
     
        ]); */
          User::create($request->all());
   
        return redirect()->route('students.index')
                        ->with('success','Student created successfully.');
    }

    public function show(User $student)
    {
        return view ('students.show',compact ('student'));
    }

    public function destroy(User $student)
    {
         $student->delete();
  
        return redirect()->route('students.index')
                        ->with('success','Student deleted successfully');
    }

    public function edit(User $student)
    {
        return view('students.edit',compact('student'));
    }

   public function update(Request $request, $student)
    {
         $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'phone_number' => 'required',
            'address' => 'required'
        ]);

        DB::table('users')->where('id',$request->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'password' => Hash::make($request->password)

        ]);
  
        // $student->update($request->all());
  
        return redirect()->route('students.index')
                        ->with('success','Student updated successfully');
    }
}