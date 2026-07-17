<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $subject->name }}のカードを編集</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 24px;
            background: #f4f4f4;
            color: #222;
            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
        }

        .container {
            width: min(100%, 720px);
            margin: 0 auto;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #222;
        }

        .panel {
            padding: 24px;
            border: 1px solid #bbb;
            border-radius: 12px;
            background: #fff;
        }

        h1 {
            margin-top: 0;
        }

        .form-group {
            margin-bottom: 22px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
        }

        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #999;
            border-radius: 8px;
            background: #fff;
            font: inherit;
        }

        textarea {
            min-height: 130px;
            resize: vertical;
        }

        .memo-field {
            min-height: 90px;
        }

        .mastery-title {
            display: block;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .mastery-options {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
        }

        .mastery-option {
            display: block;
            margin: 0;
            cursor: pointer;
        }

        .mastery-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .mastery-option span {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 54px;
            border: 2px solid #999;
            border-radius: 10px;
            background: #fff;
            color: #222;
            font-size: 20px;
            font-weight: 700;
            transition:
                background 0.15s ease,
                color 0.15s ease,
                border-color 0.15s ease;
        }

        .mastery-option input:checked+span {
            border-color: #222;
            background: #222;
            color: #fff;
        }

        .mastery-option input:focus-visible+span {
            outline: 3px solid #999;
            outline-offset: 2px;
        }

        .mastery-help {
            margin: 9px 0 0;
            color: #666;
            font-size: 13px;
        }

        .submit-button {
            width: 100%;
            padding: 13px 18px;
            border: 1px solid #222;
            border-radius: 8px;
            background: #222;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }

        .note {
            color: #666;
            font-size: 14px;
        }

        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #999;
            border-radius: 8px;
            background: #fff;
            font: inherit;
        }

        .current-image {
            margin-bottom: 14px;
            padding: 14px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background: #f8f8f8;
        }

        .current-image-label {
            margin: 0 0 10px;
            font-weight: 700;
        }

        .edit-card-image {
            display: block;
            width: auto;
            max-width: 100%;
            max-height: 300px;
            margin: 0 auto 12px;
            object-fit: contain;
            border-radius: 8px;
            background: #fff;
        }

        .delete-image-label,
        .color-option {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 12px 0 0;
            font-weight: 400;
        }

        .delete-image-label input,
        .color-option input {
            width: auto;
            margin: 0;
        }

        .image-paste-area {
            min-height: 80px;
            margin-top: 12px;
        }

        .image-message {
            min-height: 1.5em;
            margin: 8px 0 0;
            color: #555;
            font-size: 14px;
        }

        @media (max-width: 520px) {
            body {
                padding: 14px;
            }

            .panel {
                padding: 16px;
            }

            .mastery-options {
                justify-content: space-between;
            }

            .mastery-options label {
                width: 18%;
            }

            .mastery-options {
                gap: 6px;
            }

            .mastery-option span {
                min-height: 52px;
                font-size: 18px;
            }

            .error {
                margin: 7px 0 0;
                color: #c00;
            }
        }
    </style>
</head>

<body>
    <main class="container">
        <a class="back-link" href="{{ route('subjects.cards.index', $subject) }}">
            ← カード一覧へ戻る
        </a>

        <section class="panel">
            <h1>{{ $subject->name }}のカードを編集</h1>

            <form id="cardForm" action="{{ route('subjects.cards.update', [$subject, $card]) }}" method="POST"
                enctype="multipart/form-data"> @csrf
                @method('PUT')@php
                    $groupedCategories = $categories->groupBy('parent_id');
                @endphp

                <div class="form-group">
                    <label for="category_id">
                        カテゴリー（任意）
                    </label>

                    <select id="category_id" name="category_id">
                        <option value="">
                            カテゴリーなし
                        </option>

                        @foreach ($groupedCategories->get(null, collect()) as $level1)
                            <option value="{{ $level1->id }}" @selected((string) old('category_id', $card->category_id) === (string) $level1->id)>
                                {{ $level1->name }}
                            </option>

                            @foreach ($groupedCategories->get($level1->id, collect()) as $level2)
                                <option value="{{ $level2->id }}" @selected((string) old('category_id') === (string) $level2->id)>
                                    └ {{ $level2->name }}
                                </option>

                                @foreach ($groupedCategories->get($level2->id, collect()) as $level3)
                                    <option value="{{ $level3->id }}" @selected((string) old('category_id') === (string) $level3->id)>
                                        &nbsp;&nbsp;&nbsp;└ {{ $level3->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        @endforeach
                    </select>

                    @error('category_id')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="front_text">
                        表の文章
                    </label>

                    <textarea id="front_text" name="front_text" placeholder="例：三平方の定理の式は？">{{ old('front_text', $card->front_text) }}</textarea>

                    @error('front_text')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="front_image">
                        表の画像
                    </label>

                    @if ($card->front_image_url)
                        <div class="current-image">
                            <p class="current-image-label">
                                現在登録されている画像
                            </p>

                            <img class="edit-card-image" src="{{ $card->front_image_url }}" alt="現在の表画像">

                            <label class="delete-image-label">
                                <input type="checkbox" name="delete_front_image" value="1"
                                    @checked(old('delete_front_image'))>
                                現在の表画像を削除する
                            </label>
                        </div>
                    @endif

                    <input id="front_image" name="front_image" type="file" accept="image/*">

                    <textarea id="frontImagePasteArea" class="image-paste-area" placeholder="ここを選択して、画像を貼り付けられます"></textarea>

                    <label class="color-option">
                        <input id="saveFrontImageInColor" type="checkbox">
                        カラーで保存する
                    </label>

                    <p id="frontImageMessage" class="image-message" aria-live="polite"></p>

                    @error('front_image')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="back_text">
                        裏の文章
                    </label>

                    <textarea id="back_text" name="back_text" placeholder="例：a²＋b²＝c²">{{ old('back_text', $card->back_text) }}</textarea>

                    @error('back_text')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="back_image">
                        裏の画像
                    </label>

                    @if ($card->back_image_url)
                        <div class="current-image">
                            <p class="current-image-label">
                                現在登録されている画像
                            </p>

                            <img class="edit-card-image" src="{{ $card->back_image_url }}" alt="現在の裏画像">

                            <label class="delete-image-label">
                                <input type="checkbox" name="delete_back_image" value="1"
                                    @checked(old('delete_back_image'))>
                                現在の裏画像を削除する
                            </label>
                        </div>
                    @endif

                    <input id="back_image" name="back_image" type="file" accept="image/*">

                    <textarea id="backImagePasteArea" class="image-paste-area" placeholder="ここを選択して、画像を貼り付けられます"></textarea>

                    <label class="color-option">
                        <input id="saveBackImageInColor" type="checkbox">
                        カラーで保存する
                    </label>

                    <p id="backImageMessage" class="image-message" aria-live="polite"></p>

                    @error('back_image')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="memo">
                        メモ（任意）
                    </label>

                    <textarea class="memo-field" id="memo" name="memo" placeholder="補足事項など">{{ old('memo', $card->memo) }}</textarea>
                </div>

                <div class="form-group">
                    <span class="mastery-title">習熟度</span>

                    <div class="mastery-options">
                        @for ($level = 1; $level <= 5; $level++)
                            <label class="mastery-option">
                                <input type="radio" name="mastery_level" value="{{ $level }}"
                                    @checked((int) old('mastery_level', $card->mastery_level) === $level)>
                                <span>{{ $level }}</span> </label>
                        @endfor
                    </div>

                    <p class="mastery-help">
                        1：まだ覚えていない ／ 5：よく覚えている
                    </p>

                    @error('mastery_level')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <button class="submit-button" type="submit">
                    更新する
                </button>
            </form>
        </section>
    </main>
</body>

</html>
