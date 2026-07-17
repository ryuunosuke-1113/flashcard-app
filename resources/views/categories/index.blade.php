<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $subject->name }}のカテゴリー</title>

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
            width: min(100%, 680px);
            margin: 0 auto;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 16px;
            color: #333;
        }

        h1 {
            margin-bottom: 8px;
        }

        .subtitle {
            margin-top: 0;
            color: #666;
        }

        .panel {
            margin-top: 24px;
            padding: 24px;
            border: 1px solid #ccc;
            border-radius: 12px;
            background: #fff;
        }

        .category-form {
            display: flex;
            gap: 8px;
        }

        .category-form input {
            flex: 1;
            min-width: 0;
            padding: 12px;
            border: 1px solid #999;
            border-radius: 8px;
            font-size: 16px;
        }

        button {
            padding: 10px 16px;
            border: 1px solid #222;
            border-radius: 8px;
            background: #222;
            color: #fff;
            cursor: pointer;
        }

        .category-list {
            margin: 24px 0 0;
            padding: 0;
            list-style: none;
        }

        .category-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 0;
            border-bottom: 1px solid #ddd;
        }

        .category-name {
            font-size: 18px;
            font-weight: 600;
        }

        .delete-button {
            border-color: #777;
            background: #fff;
            color: #222;
        }

        .message {
            margin-top: 16px;
            padding: 12px;
            border: 1px solid #777;
            border-radius: 8px;
            background: #f8f8f8;
        }

        .error {
            margin-top: 8px;
            color: #c00;
        }

        .empty-message {
            margin-top: 24px;
            color: #666;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #999;
            border-radius: 8px;
            background: #fff;
            font: inherit;
        }

        .error {
            margin: 7px 0 0;
            color: #c00;
        }

        .container {
            width: min(100%, 960px);
            margin: 0 auto;
        }

        .panel {
            padding: 24px;
            border: 1px solid #bbb;
            border-radius: 12px;
            background: #fff;
        }

        .root-category-form {
            margin-bottom: 28px;
            padding-bottom: 22px;
            border-bottom: 1px solid #ccc;
        }

        .root-category-form h2 {
            margin: 0 0 12px;
            font-size: 18px;
        }

        .category-tree {
            display: grid;
            gap: 14px;
        }

        .category-node {
            border-left: 3px solid #ccc;
        }

        .level-1 {
            padding-left: 14px;
        }

        .level-2 {
            margin-top: 12px;
            margin-left: 26px;
            padding-left: 14px;
        }

        .level-3 {
            margin-top: 12px;
            margin-left: 26px;
            padding-left: 14px;
        }

        .category-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 9px;
            background: #fafafa;
        }

        .category-name {
            min-width: 120px;
        }

        .tree-mark {
            color: #777;
            font-weight: 700;
        }

        .inline-form {
            display: flex;
            flex: 1 1 320px;
            gap: 8px;
            margin: 0;
        }

        .inline-form input[type="text"] {
            flex: 1;
            min-width: 150px;
            padding: 9px 11px;
            border: 1px solid #999;
            border-radius: 8px;
            font: inherit;
        }

        .inline-form button {
            padding: 9px 14px;
            border: 1px solid #222;
            border-radius: 8px;
            background: #222;
            color: #fff;
            cursor: pointer;
        }

        .delete-form {
            margin: 0;
        }

        .delete-button {
            padding: 9px 14px;
            border: 1px solid #b00020;
            border-radius: 8px;
            background: #fff;
            color: #b00020;
            cursor: pointer;
        }

        .delete-button:hover {
            background: #b00020;
            color: #fff;
        }

        .depth-limit {
            padding: 5px 9px;
            border-radius: 999px;
            background: #eee;
            color: #666;
            font-size: 13px;
        }

        .success-message {
            padding: 10px 12px;
            border-left: 4px solid #2e7d32;
            background: #f3fff4;
        }

        .error {
            padding: 10px 12px;
            border-left: 4px solid #c00;
            background: #fff4f4;
            color: #c00;
        }

        .empty-message {
            color: #666;
        }

        @media (max-width: 640px) {
            .panel {
                padding: 16px;
            }

            .level-2,
            .level-3 {
                margin-left: 12px;
            }

            .category-row {
                align-items: stretch;
            }

            .category-name {
                width: 100%;
                min-width: 0;
            }

            .inline-form {
                flex-basis: 100%;
            }

            .inline-form input[type="text"] {
                min-width: 0;
            }
        }

        @media (max-width: 520px) {
            body {
                padding: 14px;
            }

            .panel {
                padding: 16px;
            }

            .category-form {
                flex-direction: column;
            }

            .category-form button {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <main class="container">
        <a class="back-link" href="{{ route('subjects.index') }}">
            ← 科目一覧へ戻る
        </a>

        <section class="panel">
            <h1>{{ $subject->name }}のカテゴリー管理</h1>

            @if (session('success'))
                <p class="success-message">
                    {{ session('success') }}
                </p>
            @endif

            @error('name')
                <p class="error">{{ $message }}</p>
            @enderror

            @error('delete')
                <p class="error">{{ $message }}</p>
            @enderror

            <section class="root-category-form">
                <h2>第1階層を追加</h2>

                <form action="{{ route('subjects.categories.store', $subject) }}" method="POST" class="inline-form">
                    @csrf

                    <input type="text" name="name" placeholder="例：物理" required>

                    <input type="hidden" name="parent_id" value="">

                    <button type="submit">
                        追加
                    </button>
                </form>
            </section>

            <section class="category-tree">
                @forelse ($rootCategories as $rootCategory)
                    <article class="category-node level-1">
                        <div class="category-row">
                            <strong class="category-name">
                                {{ $rootCategory->name }}
                            </strong>

                            <form action="{{ route('subjects.categories.store', $subject) }}" method="POST"
                                class="inline-form child-form">
                                @csrf

                                <input type="text" name="name" placeholder="この下に追加" required>

                                <input type="hidden" name="parent_id" value="{{ $rootCategory->id }}">

                                <button type="submit">
                                    追加
                                </button>
                            </form>

                            <form action="{{ route('subjects.categories.destroy', [$subject, $rootCategory]) }}"
                                method="POST" class="delete-form" onsubmit="return confirm('このカテゴリーを削除しますか？');">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="delete-button">
                                    削除
                                </button>
                            </form>
                        </div>

                        @foreach ($rootCategory->children as $childCategory)
                            <article class="category-node level-2">
                                <div class="category-row">
                                    <span class="tree-mark">└</span>

                                    <strong class="category-name">
                                        {{ $childCategory->name }}
                                    </strong>

                                    <form action="{{ route('subjects.categories.store', $subject) }}" method="POST"
                                        class="inline-form child-form">
                                        @csrf

                                        <input type="text" name="name" placeholder="この下に追加" required>

                                        <input type="hidden" name="parent_id" value="{{ $childCategory->id }}">

                                        <button type="submit">
                                            追加
                                        </button>
                                    </form>

                                    <form
                                        action="{{ route('subjects.categories.destroy', [$subject, $childCategory]) }}"
                                        method="POST" class="delete-form"
                                        onsubmit="return confirm('このカテゴリーを削除しますか？');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="delete-button">
                                            削除
                                        </button>
                                    </form>
                                </div>

                                @foreach ($childCategory->children as $grandchildCategory)
                                    <article class="category-node level-3">
                                        <div class="category-row">
                                            <span class="tree-mark">└</span>

                                            <strong class="category-name">
                                                {{ $grandchildCategory->name }}
                                            </strong>

                                            <span class="depth-limit">
                                                第3階層
                                            </span>

                                            <form
                                                action="{{ route('subjects.categories.destroy', [$subject, $grandchildCategory]) }}"
                                                method="POST" class="delete-form"
                                                onsubmit="return confirm('このカテゴリーを削除しますか？');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="delete-button">
                                                    削除
                                                </button>
                                            </form>
                                        </div>
                                    </article>
                                @endforeach
                            </article>
                        @endforeach
                    </article>
                @empty
                    <p class="empty-message">
                        カテゴリーはまだありません。
                    </p>
                @endforelse
            </section>
        </section>
    </main>
</body>

</html>
