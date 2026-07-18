<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Models\Card;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class CardController extends Controller
{
    public function index(Subject $subject): View
    {
        $cards = $subject->cards()
            ->with('category')
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        return view(
            'cards.index',
            compact('subject', 'cards')
        );
    }

    public function create(Subject $subject): View
    {
        $categories = $subject->categories()
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        return view(
            'cards.create',
            compact('subject', 'categories')
        );
    }

    public function store(
        Request $request,
        Subject $subject
    ): RedirectResponse {
        $validated = $request->validate([
            'category_id' => [
                'nullable',
                'integer',
                Rule::exists('categories', 'id')
                    ->where(
                        'subject_id',
                        $subject->id
                    ),
            ],

            'front_text' => [
                'nullable',
                'string',
                'max:10000',
                'required_without:front_image',
            ],

            'front_image' => [
                'nullable',
                'file',
                'image',
                'mimes:webp,jpeg,jpg,png,gif',
                'max:5120',
                'required_without:front_text',
            ],

            'back_text' => [
                'nullable',
                'string',
                'max:10000',
                'required_without:back_image',
            ],

            'back_image' => [
                'nullable',
                'file',
                'image',
                'mimes:webp,jpeg,jpg,png,gif',
                'max:5120',
                'required_without:back_text',
            ],

            'memo' => [
                'nullable',
                'string',
                'max:10000',
            ],

            'mastery_level' => [
                'required',
                'integer',
                'between:1,5',
            ],
        ], [
            'category_id.exists' =>
                '選択したカテゴリーが正しくありません。',

            'front_text.required_without' =>
                '表の文章または表の画像を登録してください。',

            'front_image.required_without' =>
                '表の文章または表の画像を登録してください。',

            'front_image.image' =>
                '表の画像には画像ファイルを選択してください。',

            'front_image.mimes' =>
                '表の画像はWebP、JPEG、PNG、GIF形式で登録してください。',

            'front_image.max' =>
                '表の画像は5MB以下にしてください。',

            'back_text.required_without' =>
                '裏の文章または裏の画像を登録してください。',

            'back_image.required_without' =>
                '裏の文章または裏の画像を登録してください。',

            'back_image.image' =>
                '裏の画像には画像ファイルを選択してください。',

            'back_image.mimes' =>
                '裏の画像はWebP、JPEG、PNG、GIF形式で登録してください。',

            'back_image.max' =>
                '裏の画像は5MB以下にしてください。',

            'mastery_level.required' =>
                '習熟度を選択してください。',

            'mastery_level.between' =>
                '習熟度は1から5で選択してください。',
        ]);

        $frontImageUrl = null;

        if ($request->hasFile('front_image')) {
            $frontImagePath = $request
                ->file('front_image')
                ->store(
                    "cards/{$subject->id}/front",
                    'public'
                );

            $frontImageUrl = Storage::disk('public')
                ->url($frontImagePath);
        }

        $backImageUrl = null;

        if ($request->hasFile('back_image')) {
            $backImagePath = $request
                ->file('back_image')
                ->store(
                    "cards/{$subject->id}/back",
                    'public'
                );

            $backImageUrl = Storage::disk('public')
                ->url($backImagePath);
        }

        $card = $subject->cards()->create([
            'category_id' =>
                $validated['category_id'] ?? null,

            'front_text' =>
                $validated['front_text'] ?? null,

            'front_image_url' =>
                $frontImageUrl,

            'back_text' =>
                $validated['back_text'] ?? null,

            'back_image_url' =>
                $backImageUrl,

            'memo' =>
                $validated['memo'] ?? null,

            'mastery_level' =>
                $validated['mastery_level'],
        ]);

        return redirect()
            ->route('subjects.cards.index', $subject)
            ->with('success', 'カードを登録しました。');
    }
    public function edit(
        Subject $subject,
        Card $card
    ): View {
        if ($card->subject_id !== $subject->id) {
            abort(404);
        }

        $categories = $subject->categories()
            ->orderBy('name')
            ->get();

        return view('cards.edit', [
            'subject' => $subject,
            'card' => $card,
            'categories' => $categories,
        ]);
    }
    public function update(
        Request $request,
        Subject $subject,
        Card $card
    ): RedirectResponse {
        if ($card->subject_id !== $subject->id) {
            abort(404);
        }

        $validated = $request->validate(
            [
                'category_id' => [
                    'nullable',
                    Rule::exists('categories', 'id')
                        ->where(
                            fn($query) =>
                            $query->where(
                                'subject_id',
                                $subject->id
                            )
                        ),
                ],

                'front_text' => [
                    'nullable',
                    'string',
                    'max:10000',
                ],

                'front_image' => [
                    'nullable',
                    'file',
                    'image',
                    'mimes:webp,jpeg,jpg,png,gif',
                    'max:5120',
                ],

                'back_text' => [
                    'nullable',
                    'string',
                    'max:10000',
                ],

                'back_image' => [
                    'nullable',
                    'file',
                    'image',
                    'mimes:webp,jpeg,jpg,png,gif',
                    'max:5120',
                ],

                'delete_front_image' => [
                    'nullable',
                    'boolean',
                ],

                'delete_back_image' => [
                    'nullable',
                    'boolean',
                ],

                'memo' => [
                    'nullable',
                    'string',
                    'max:10000',
                ],

                'mastery_level' => [
                    'required',
                    'integer',
                    'between:1,5',
                ],
            ],
            [
                'front_text.required_without_all' =>
                    '表の文章または表の画像を登録してください。',

                'front_image.image' =>
                    '表の画像には画像ファイルを選択してください。',

                'front_image.max' =>
                    '表の画像は5MB以下にしてください。',

                'back_text.required_without_all' =>
                    '裏の文章または裏の画像を登録してください。',

                'back_image.image' =>
                    '裏の画像には画像ファイルを選択してください。',

                'back_image.max' =>
                    '裏の画像は5MB以下にしてください。',
            ]
        );

        $frontImageUrl =
            $card->front_image_url;

        $backImageUrl =
            $card->back_image_url;

        /*
         * 削除チェックが入っている場合は、
         * 既存画像を削除します。
         */
        if ($request->boolean('delete_front_image')) {
            $this->deletePublicImage(
                $card->front_image_url
            );

            $frontImageUrl = null;
        }

        if ($request->boolean('delete_back_image')) {
            $this->deletePublicImage(
                $card->back_image_url
            );

            $backImageUrl = null;
        }

        /*
         * 新しい画像が送信された場合は、
         * 既存画像を削除して差し替えます。
         */
        if ($request->hasFile('front_image')) {
            $this->deletePublicImage(
                $card->front_image_url
            );

            $frontImagePath =
                $request
                    ->file('front_image')
                    ->store(
                        "cards/{$subject->id}/front",
                        'public'
                    );

            $frontImageUrl =
                Storage::disk('public')
                    ->url($frontImagePath);
        }

        if ($request->hasFile('back_image')) {
            $this->deletePublicImage(
                $card->back_image_url
            );

            $backImagePath =
                $request
                    ->file('back_image')
                    ->store(
                        "cards/{$subject->id}/back",
                        'public'
                    );

            $backImageUrl =
                Storage::disk('public')
                    ->url($backImagePath);
        }

        /*
         * 最終的に、文章も画像もない状態は許可しません。
         */
        $frontText =
            isset($validated['front_text'])
            ? trim($validated['front_text'])
            : null;

        $backText =
            isset($validated['back_text'])
            ? trim($validated['back_text'])
            : null;

        if (
            blank($frontText) &&
            blank($frontImageUrl)
        ) {
            return back()
                ->withErrors([
                    'front_text' =>
                        '表の文章または表の画像を登録してください。',
                ])
                ->withInput();
        }

        if (
            blank($backText) &&
            blank($backImageUrl)
        ) {
            return back()
                ->withErrors([
                    'back_text' =>
                        '裏の文章または裏の画像を登録してください。',
                ])
                ->withInput();
        }

        $card->update([
            'category_id' =>
                $validated['category_id'] ?? null,

            'front_text' =>
                blank($frontText)
                ? null
                : $frontText,

            'front_image_url' =>
                $frontImageUrl,

            'back_text' =>
                blank($backText)
                ? null
                : $backText,

            'back_image_url' =>
                $backImageUrl,

            'memo' =>
                $validated['memo'] ?? null,

            'mastery_level' =>
                $validated['mastery_level'],
        ]);

        return redirect()
            ->route(
                'subjects.cards.index',
                $subject
            )
            ->with(
                'success',
                'カードを更新しました。'
            );
    }
    private function deletePublicImage(
        ?string $imageUrl
    ): void {
        if (blank($imageUrl)) {
            return;
        }

        $storagePrefix =
            Storage::disk('public')
                ->url('');

        $imagePath =
            str_starts_with(
                $imageUrl,
                $storagePrefix
            )
            ? substr(
                $imageUrl,
                strlen($storagePrefix)
            )
            : null;

        if (
            $imagePath &&
            Storage::disk('public')
                ->exists($imagePath)
        ) {
            Storage::disk('public')
                ->delete($imagePath);
        }
    }
    public function destroy(
        Subject $subject,
        Card $card
    ): RedirectResponse {
        if ($card->subject_id !== $subject->id) {
            abort(404);
        }

        $card->delete();

        return redirect()
            ->route('subjects.cards.index', $subject)
            ->with('success', 'カードを削除しました。');
    }
    public function study(Subject $subject): View
    {
        $cards = $subject->cards()
            ->with('category')
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        return view(
            'cards.study',
            compact('subject', 'cards')
        );
    }

    public function markAsStudied(
        Subject $subject,
        Card $card
    ): JsonResponse {
        if ($card->subject_id !== $subject->id) {
            abort(404);
        }

        $card->update([
            'last_studied_at' => now(),
        ]);

        return response()->json([
            'message' => '最終取り組み日を更新しました。',
            'last_studied_at' => $card->last_studied_at
                ->format('Y/m/d'),
        ]);
    }
    public function updateMastery(
        Request $request,
        Subject $subject,
        Card $card
    ): JsonResponse {
        if ($card->subject_id !== $subject->id) {
            abort(404);
        }

        $validated = $request->validate([
            'mastery_level' => ['required', 'integer', 'between:1,5'],
        ]);

        $card->update([
            'mastery_level' => $validated['mastery_level'],
        ]);

        return response()->json([
            'message' => '習熟度を更新しました。',
            'mastery_level' => $card->mastery_level,
        ]);
    }
    public function toggleBookmark(
        Subject $subject,
        Card $card
    ): JsonResponse {
        if ((int) $card->subject_id !== (int) $subject->id) {
            abort(404);
        }

        $wasBookmarked = (bool) $card->is_bookmarked;

        if ($wasBookmarked) {
            $card->is_bookmarked = false;
            $card->save();
        } else {
            Card::query()
                ->where('subject_id', $subject->id)
                ->where('id', '!=', $card->id)
                ->update([
                    'is_bookmarked' => false,
                ]);

            $card->is_bookmarked = true;
            $card->save();
        }

        $card->refresh();

        return response()->json([
            'message' => $card->is_bookmarked
                ? 'このカードを再開位置に設定しました。'
                : '再開位置を解除しました。',
            'is_bookmarked' => (bool) $card->is_bookmarked,
        ]);
    }
}