<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    // Show list
    public function index()
    {
        $subjects = Subject::latest()->paginate(10);
        return view('subjects.index', compact('subjects'));
    }

    // Show create form
    public function create()
    {
        return view('subjects.create');
    }

    // Store new subject
    public function store(Request $request)
    {
        $request->validate([
            'subject_name' => 'required|string|max:255',
            'subject_code' => 'required|string|max:50',
            'lecturer_name' => 'required|string|max:255',
        ]);

        Subject::create($request->only(['subject_name', 'subject_code', 'lecturer_name']));

        return redirect()->route('subjects.index')
            ->with('success', 'Subject added successfully.');
    }

    // Edit form
    public function edit(Subject $subject)
    {
        return view('subjects.edit', compact('subject'));
    }

    // Update subject
    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'subject_name' => 'required|string|max:255',
            'subject_code' => 'required|string|max:50',
            'lecturer_name' => 'required|string|max:255',
        ]);

        $subject->update($request->only(['subject_name', 'subject_code', 'lecturer_name']));

        return redirect()->route('subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    // Delete
    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route('subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }
    public function show($id)
{
    $subject = Subject::findOrFail($id);

    return view('subjects.show', compact('subject'));
}

}
