<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Students;
use App\Models\Halls;
use App\Models\Subjects;
use App\Models\Groups;
use App\Models\Days;
use App\Models\Timetables;

class DashboardController extends Controller
{
    public function index()
    {
         $studentsCount = Student::count();
        $hallsCount    = Hall::count();
        $subjectsCount = Subject::count();
        $groupsCount   = Group::count();

        return view('dashboard', compact('studentsCount', 'hallsCount', 'subjectsCount', 'groupsCount'));
    }
  }

