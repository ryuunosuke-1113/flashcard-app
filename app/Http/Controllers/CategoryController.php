<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Subject $subject): View
    {
        $rootCategories = $subject->categories()
            ->whereNull('parent_id')
            ->with([
                'children' => fn($query) =>
                    $query->orderBy('name'),
                'children.children' => fn($query) =>
                    $query->orderBy('name'),
            ])
            ->orderBy('name')
            ->get();

        return view('categories.index', [
            'subject' => $subject,
            'rootCategories' => $rootCategories,
        ]);
    }
    public function store(
        Request $request,
        Subject $subject
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'parent_id' => [
                'nullable',
                Rule::exists('categories', 'id')
                    ->where(
                        fn($query) =>
                        $query->where('subject_id', $subject->id)
                    ),
            ],
        ]);

        $parentId = $validated['parent_id'] ?? null;

        if ($parentId !== null) {
            $parent = $subject->categories()
                ->with('parent.parent')
                ->findOrFail($parentId);

            $depth = 1;
            $current = $parent;

            while ($current->parent !== null) {
                $depth++;
                $current = $current->parent;
            }

            if ($depth >= 3) {
                return back()
                    ->withErrors([
                        'name' =>
                            '第3階層の下にはカテゴリーを追加できません。',
                    ])
                    ->withInput();
            }
        }

        $alreadyExists = $subject->categories()
            ->where('parent_id', $parentId)
            ->where('name', $validated['name'])
            ->exists();

        if ($alreadyExists) {
            return back()
                ->withErrors([
                    'name' =>
                        '同じ階層に同名のカテゴリーがあります。',
                ])
                ->withInput();
        }

        $subject->categories()->create([
            'name' => $validated['name'],
            'parent_id' => $parentId,
        ]);

        return redirect()
            ->route('subjects.categories.index', $subject)
            ->with('success', 'カテゴリーを追加しました。');
    }
    public function destroy(
        Subject $subject,
        Category $category
    ): RedirectResponse {
        if ($category->subject_id !== $subject->id) {
            abort(404);
        }

        if ($category->children()->exists()) {
            return back()->withErrors([
                'delete' =>
                    '子カテゴリーがあるため削除できません。先に下の階層を削除してください。',
            ]);
        }

        if ($category->cards()->exists()) {
            return back()->withErrors([
                'delete' =>
                    'カードで使用されているため削除できません。',
            ]);
        }

        $category->delete();

        return redirect()
            ->route('subjects.categories.index', $subject)
            ->with('success', 'カテゴリーを削除しました。');
    }
}