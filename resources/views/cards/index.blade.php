<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $subject->name }}のカード</title>

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
            width: min(100%, 760px);
            margin: 0 auto;
        }

        .navigation {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
        }

        .navigation a {
            color: #222;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 24px;
        }

        h1 {
            margin-bottom: 8px;
        }

        .subtitle {
            margin: 0;
            color: #666;
        }

        .header-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .add-button {
            display: inline-block;
            padding: 11px 18px;
            border: 1px solid #222;
            border-radius: 8px;
            background: #222;
            color: #fff;
            text-decoration: none;
        }

        .success-message {
            margin-bottom: 20px;
            padding: 12px;
            border: 1px solid #777;
            border-radius: 8px;
            background: #fff;
        }

        .empty-panel {
            padding: 40px 24px;
            border: 1px solid #bbb;
            border-radius: 12px;
            background: #fff;
            text-align: center;
        }

        .empty-panel p {
            margin: 0;
            color: #666;
        }

        .card-list {
            display: grid;
            gap: 16px;
        }

        .card-item {
            padding: 20px;
            border: 1px solid #bbb;
            border-radius: 12px;
            background: #fff;
        }

        .card-number,
        .category-name {
            margin-top: 0;
            color: #666;
        }

        .side-label {
            margin: 18px 0 6px;
            font-size: 14px;
        }

        .card-text {
            margin: 0;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        .memo-notice {
            margin: 16px 0 0;
            color: #c00;
            font-weight: bold;
        }

        .mastery {
            margin: 16px 0 0;
        }


        .edit-button:hover {
            background: #222;
            color: #fff;
        }

        .card-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 12px;
        }

        .delete-form {
            margin: 0;
            display: inline-block;
        }

        .edit-button,
        .delete-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            height: 42px;
            padding: 0 16px;

            border-radius: 8px;
        }


        .delete-button:hover {
            background: #b00020;
            color: #fff;
        }

        .card-front-image {
            display: block;
            width: 100%;
            max-height: 360px;
            margin-top: 14px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background: #fff;
            object-fit: contain;
        }

        @media (max-width: 520px) {
            body {
                padding: 14px;
            }

            .header {
                align-items: stretch;
                flex-direction: column;
            }

            .header-actions {
                flex-direction: column;
            }

            .header-actions .add-button {
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <main class="container">
        <nav class="navigation">
            <a href="{{ route('subjects.index') }}">
                ← 科目一覧
            </a>

            <a href="{{ route('subjects.categories.index', $subject) }}">
                カテゴリー管理
            </a>
        </nav>

        <header class="header">
            <div>
                <h1>{{ $subject->name }}</h1>

                <p class="subtitle">
                    この科目の暗記カード
                </p>
            </div>

            <div class="header-actions">
                <a class="add-button" href="{{ route('subjects.cards.study', $subject) }}">
                    学習を始める
                </a>

                <a class="add-button" href="{{ route('subjects.cards.create', $subject) }}">
                    ＋ カードを追加
                </a>
            </div>
        </header>

        @if (session('success'))
            <p class="success-message">
                {{ session('success') }}
            </p>
        @endif

        @if ($cards->isEmpty())
            <section class="empty-panel">
                <p>まだカードが登録されていません。</p>
            </section>
        @else
            <section class="card-list">
                @foreach ($cards as $card)
                    <article class="card-item">
                        <p class="card-number">
                            カード {{ $loop->iteration }}
                        </p>

                        @php
                            $categoryNames = [];
                            $currentCategory = $card->category;
                            $depth = 0;

                            while ($currentCategory && $depth < 3) {
                                array_unshift($categoryNames, $currentCategory->name);

                                $currentCategory = $currentCategory->parent;
                                $depth++;
                            }

                            $categoryPath = count($categoryNames) > 0 ? implode(' ＞ ', $categoryNames) : '未設定';
                        @endphp

                        <p class="category-name">
                            カテゴリー：{{ $categoryPath }}
                        </p>
                        <h2 class="side-label">表</h2>

                        @if (filled($card->front_text))
                            <p class="card-text">
                                {{ $card->front_text }}
                            </p>
                        @endif

                        @if (filled($card->front_image_url))
                            <img class="card-front-image" src="{{ $card->front_image_url }}" alt="カード表面の画像">
                        @endif
                        @if (filled($card->memo))
                            <p class="memo-notice">
                                メモがあります
                            </p>
                        @endif

                        <p class="mastery">
                            習熟度：
                            {{ str_repeat('★', $card->mastery_level) }}
                            {{ str_repeat('☆', 5 - $card->mastery_level) }}
                        </p>
                        <div class="card-actions">
                            <a class="edit-button" href="{{ route('subjects.cards.edit', [$subject, $card]) }}">
                                カードを編集
                            </a>

                            <form action="{{ route('subjects.cards.destroy', [$subject, $card]) }}" method="POST"
                                class="delete-form" onsubmit="return confirm('このカードを削除しますか？');">
                                @csrf
                                @method('DELETE')

                                <button class="delete-button" type="submit">
                                    カードを削除
                                </button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </section>
        @endif
    </main>
</body>

</html>
