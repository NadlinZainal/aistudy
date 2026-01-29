<?php

namespace App\Http\Controllers;

use App\Models\StudentTimetable;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subject;
use App\Models\Day;
use App\Models\Hall;
use App\Models\Group;


class TimetableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $timetable = StudentTimetable::all();
        //dd( $timetable );
         return view('timetable.index',compact('timetable'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $subjects = Subject::all();
        $days = Day::all();
        $halls = Hall::all();
        $groups = Group::all();

    return view('timetable.create', compact('users','subjects','days','halls','groups'));
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       /*  $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'day_id' => 'required|exists:days,id',
            'hall_id' => 'required|exists:halls,id',
            'lecturer_group_id' => 'required|exists:groups,id',
            'time_from' => 'required|string|max:10',
            'time_to' => 'required|string|max:10',
        ]); */

        StudentTimetable::create($request->all());

        return redirect()->route('timetable.index')
            ->with('success', 'Timetable entry created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentTimetable $timetable)
    {
        return view('timetable.show', compact('timetable'));
    }

    /**
     * Show the form for editing the specified resource.
     */
   public function edit(StudentTimetable $timetable)
    {
    $users = User::all();
    $subjects = Subject::all();
    $days = Day::all();
    $halls = Hall::all();
    $groups = Group::all();

    // Pass them to the view
    return view('timetable.edit', compact('timetable','users','subjects','days','halls','groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentTimetable $timetable)
    {
        // $request->validate([
        //     'name' => 'required',
        //     'email' => 'required',
        //     'password' => 'required',
        // ]);

        // DB::table('users')->where('id',$request->id)->update([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password)
        // ]);
  
        $timetable->update($request->all());
  
        return redirect()->route('timetable.index')
                        ->with('success','Timetable updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(StudentTimetable $timetable)
{
        $timetable->delete();

        return redirect()->route('timetable.index')
            ->with('success', 'Timetable deleted successfully.');
    }
}
