<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>暗記カード</title>

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

        .subject-form {
            display: flex;
            gap: 8px;
        }

        .subject-form input {
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

        .subject-list {
            margin: 24px 0 0;
            padding: 0;
            list-style: none;
        }

        .subject-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 0;
            border-bottom: 1px solid #ddd;
        }

        .subject-name {
            color: #222;
            font-size: 18px;
            font-weight: 600;
            text-decoration: none;
        }

        .subject-name:hover {
            text-decoration: underline;
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

        @media (max-width: 520px) {
            body {
                padding: 14px;
            }

            .panel {
                padding: 16px;
            }

            .subject-form {
                flex-direction: column;
            }

            .subject-form button {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <main class="container">
        <h1>暗記カード</h1>
        <p class="subtitle">科目ごとにカードを管理します。</p>

        <section class="panel">
            <h2>科目一覧</h2>

            <form class="subject-form" action="{{ route('subjects.store') }}" method="POST">
                @csrf

                <input type="text" name="name" value="{{ old('name') }}" placeholder="例：物理" maxlength="100"
                    required>

                <button type="submit">追加</button>
            </form>

            @error('name')
                <p class="error">{{ $message }}</p>
            @enderror

            @if (session('success'))
                <p class="message">{{ session('success') }}</p>
            @endif

            @if ($subjects->isEmpty())
                <p class="empty-message">
                    まだ科目が登録されていません。
                </p>
            @else
                <ul class="subject-list">
                    @foreach ($subjects as $subject)
                        <li class="subject-item">
                            <a class="subject-name" href="{{ route('subjects.cards.index', $subject) }}">
                                {{ $subject->name }}
                            </a>
                            <form action="{{ route('subjects.destroy', $subject) }}" method="POST"
                                onsubmit="return confirm('この科目を削除しますか？');">
                                @csrf
                                @method('DELETE')

                                <button class="delete-button" type="submit">
                                    削除
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </main>
</body>

</html>
