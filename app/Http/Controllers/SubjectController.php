<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(): View
    {
        $subjects = Subject::orderBy('created_at')->get();

        return view('subjects.index', compact('subjects'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:subjects,name',
            ],
        ], [
            'name.required' => '科目名を入力してください。',
            'name.max' => '科目名は100文字以内で入力してください。',
            'name.unique' => '同じ科目がすでに登録されています。',
        ]);

        Subject::create($validated);

        return redirect()
            ->route('subjects.index')
            ->with('success', '科目を追加しました。');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        $subject->delete();

        return redirect()
            ->route('subjects.index')
            ->with('success', '科目を削除しました。');
    }
}