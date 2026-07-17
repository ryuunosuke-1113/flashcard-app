<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $subject->name }}の学習</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 24px;
            background: #f1f1f1;
            color: #222;
            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
        }

        button {
            font: inherit;
        }

        .container {
            width: min(100%, 760px);
            margin: 0 auto;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #222;
        }

        .study-header {
            margin-bottom: 20px;
        }

        .study-header h1 {
            margin: 0 0 6px;
        }

        .progress {
            margin: 0;
            color: #666;
        }

        .card-scene {
            width: 100%;
            min-height: 390px;
            perspective: 1200px;
        }

        .flashcard {
            position: relative;
            width: 100%;
            min-height: 390px;
            border: 0;
            padding: 0;
            background: transparent;
            cursor: pointer;
            transform-style: preserve-3d;
            transition: transform 0.55s ease;
        }

        .flashcard.is-flipped {
            transform: rotateY(180deg);
        }

        .card-face {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 390px;
            padding: 32px;
            border: 2px solid #222;
            border-radius: 18px;
            background: #fff;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
            overflow: auto;
        }

        .card-back {
            transform: rotateY(180deg);
        }

        .side-label {
            position: absolute;
            top: 20px;
            left: 24px;
            margin: 0;
            color: #666;
            font-size: 14px;
            font-weight: 700;
        }

        .card-text {
            margin: 0;
            font-size: clamp(24px, 5vw, 38px);
            line-height: 1.6;
            text-align: center;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        .tap-guide {
            position: absolute;
            right: 20px;
            bottom: 16px;
            margin: 0;
            color: #777;
            font-size: 13px;
        }

        .information {
            margin-top: 18px;
            padding: 18px;
            border: 1px solid #bbb;
            border-radius: 12px;
            background: #fff;
        }

        .category,
        .mastery {
            margin: 0;
        }

        .mastery {
            margin-top: 8px;
        }

        .mastery-update {
            margin-top: 16px;
        }

        .mastery-update-label {
            margin: 0 0 8px;
            color: #555;
            font-size: 14px;
        }

        .mastery-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .mastery-button {
            width: 42px;
            height: 42px;
            border: 1px solid #222;
            border-radius: 8px;
            background: #fff;
            color: #222;
            cursor: pointer;
            transition:
                background 0.15s ease,
                color 0.15s ease;
        }

        .mastery-button:hover,
        .mastery-button.active {
            background: #222;
            color: #fff;
        }

        .mastery-button:disabled {
            cursor: wait;
            opacity: 0.65;
        }

        .mastery-message {
            min-height: 1.2em;
            margin: 10px 0 0;
            color: #2e7d32;
            font-size: 14px;
        }

        .last-studied {
            margin: 12px 0 0;
            color: #555;
        }

        .studied-button {
            margin-top: 14px;
            padding: 11px 16px;
            border: 1px solid #222;
            border-radius: 9px;
            background: #222;
            color: #fff;
            cursor: pointer;
        }

        .studied-button:disabled {
            border-color: #aaa;
            background: #aaa;
            cursor: wait;
        }

        .studied-message {
            min-height: 1.5em;
            margin: 8px 0 0;
            color: #444;
            font-size: 14px;
        }

        .memo-button {
            margin-top: 12px;
            padding: 0;
            border: 0;
            background: transparent;
            color: #c00;
            font-weight: 700;
            cursor: pointer;
        }

        .memo {
            display: none;
            margin: 12px 0 0;
            padding: 14px;
            border-left: 4px solid #c00;
            background: #f6f6f6;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        .memo.is-visible {
            display: block;
        }

        .controls {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 12px;
            margin-top: 18px;
        }

        .control-button {
            padding: 12px 18px;
            border: 1px solid #222;
            border-radius: 9px;
            background: #222;
            color: #fff;
            cursor: pointer;
        }

        .control-button:disabled {
            border-color: #aaa;
            background: #ccc;
            color: #666;
            cursor: not-allowed;
        }

        .previous-button {
            justify-self: start;
        }

        .next-button {
            justify-self: end;
        }

        .counter {
            color: #555;
            font-weight: 700;
        }

        .empty-panel {
            padding: 40px 24px;
            border: 1px solid #bbb;
            border-radius: 12px;
            background: #fff;
            text-align: center;
        }

        .bookmark-button {
            margin-top: 14px;
            padding: 10px 16px;
            border: 1px solid #8a6d00;
            border-radius: 9px;
            background: #fff;
            color: #8a6d00;
            font-weight: 700;
            cursor: pointer;
        }

        .bookmark-button.is-bookmarked {
            background: #8a6d00;
            color: #fff;
        }

        .bookmark-button:disabled {
            opacity: 0.65;
            cursor: wait;
        }

        .bookmark-message {
            min-height: 1.4em;
            margin: 8px 0 0;
            color: #555;
            font-size: 14px;
        }

        .study-mode-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 14px;
        }

        .study-mode-button {
            padding: 9px 14px;
            border: 1px solid #222;
            border-radius: 8px;
            background: #fff;
            color: #222;
            cursor: pointer;
        }

        .study-mode-button.active {
            background: #222;
            color: #fff;
        }

        .filter-panel {
            margin: 18px 0;
            padding: 18px;
            border: 1px solid #bbb;
            border-radius: 12px;
            background: #fff;
        }

        .filter-panel h2 {
            margin: 0 0 16px;
            font-size: 18px;
        }

        .filter-group {
            margin-bottom: 16px;
        }

        .filter-group label,
        .filter-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .filter-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #999;
            border-radius: 8px;
            background: #fff;
            font: inherit;
        }

        .filter-mastery-options {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
        }

        .filter-mastery-option {
            margin: 0;
            cursor: pointer;
        }

        .filter-mastery-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .filter-mastery-option span {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            border: 1px solid #999;
            border-radius: 8px;
            background: #fff;
            color: #222;
            font-weight: 700;
        }

        .filter-mastery-option input:checked+span {
            border-color: #222;
            background: #222;
            color: #fff;
        }

        .filter-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .filter-button {
            padding: 10px 14px;
            border: 1px solid #222;
            border-radius: 8px;
            background: #222;
            color: #fff;
            cursor: pointer;
        }

        .filter-button.secondary {
            background: #fff;
            color: #222;
        }

        .filter-message {
            min-height: 1.4em;
            margin: 10px 0 0;
            color: #555;
            font-size: 14px;
        }

        .study-card-image {
            display: block;
            width: auto;
            max-width: 100%;
            height: auto;
            max-height: 420px;
            margin: 14px auto 0;
            object-fit: contain;
            border-radius: 10px;
            background: #fff;
            cursor: zoom-in;
        }

        .study-card-image[hidden] {
            display: none;
        }

        .image-lightbox {
            position: fixed;
            inset: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(0, 0, 0, 0.85);
            cursor: zoom-out;
        }

        .image-lightbox[hidden] {
            display: none;
        }

        .lightbox-image {
            display: block;
            width: auto;
            max-width: 95vw;
            height: auto;
            max-height: 85vh;
            object-fit: contain;
            border-radius: 10px;
            background: #fff;
        }

        .lightbox-guide {
            margin: 12px 0 0;
            color: #fff;
            font-size: 14px;
        }

        @media (max-width: 520px) {
            body {
                padding: 14px;
            }

            .card-scene,
            .flashcard,
            .card-face {
                min-height: 330px;
            }

            .card-face {
                padding: 28px 20px;
            }

            .controls {
                grid-template-columns: 1fr 1fr;
            }

            .counter {
                grid-column: 1 / -1;
                grid-row: 1;
                text-align: center;
            }

            .previous-button {
                grid-column: 1;
                grid-row: 2;
            }

            .next-button {
                grid-column: 2;
                grid-row: 2;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .flashcard,
            .mastery-button {
                transition: none;
            }
        }
    </style>
</head>

<body>
    <main class="container">
        <a class="back-link" href="{{ route('subjects.cards.index', $subject) }}">
            ← カード一覧へ戻る
        </a>

        <header class="study-header">
            <h1>{{ $subject->name }}</h1>

            <p class="progress">
                作成順で学習します。
            </p>

            <div class="study-mode-buttons">
                <section class="filter-panel">
                    <h2>絞り込み</h2>

                    <div class="filter-group">
                        <label for="categoryFilter">
                            カテゴリー
                        </label>

                        @php
                            $filterCategories = $subject->categories()->orderBy('name')->get()->groupBy('parent_id');
                        @endphp

                        <select id="categoryFilter">
                            <option value="" data-category-ids="[]">
                                すべて
                            </option>

                            @foreach ($filterCategories->get(null, collect()) as $level1)
                                @php
                                    $level2Categories = $filterCategories->get($level1->id, collect());

                                    $level3CategoryIds = $level2Categories->flatMap(function ($level2) use (
                                        $filterCategories,
                                    ) {
                                        return $filterCategories->get($level2->id, collect())->pluck('id');
                                    });

                                    $level1TargetIds = collect([$level1->id])
                                        ->merge($level2Categories->pluck('id'))
                                        ->merge($level3CategoryIds)
                                        ->values();
                                @endphp

                                <option value="{{ $level1->id }}" data-category-ids='@json($level1TargetIds)'>
                                    {{ $level1->name }}
                                </option>

                                @foreach ($level2Categories as $level2)
                                    @php
                                        $level3Categories = $filterCategories->get($level2->id, collect());

                                        $level2TargetIds = collect([$level2->id])
                                            ->merge($level3Categories->pluck('id'))
                                            ->values();
                                    @endphp

                                    <option value="{{ $level2->id }}"
                                        data-category-ids='@json($level2TargetIds)'>
                                        └ {{ $level2->name }}
                                    </option>

                                    @foreach ($level3Categories as $level3)
                                        <option value="{{ $level3->id }}"
                                            data-category-ids='@json([$level3->id])'>
                                            &nbsp;&nbsp;&nbsp;└ {{ $level3->name }}
                                        </option>
                                    @endforeach
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <span class="filter-label">
                            習熟度
                        </span>

                        <div class="filter-mastery-options">
                            @for ($level = 1; $level <= 5; $level++)
                                <label class="filter-mastery-option">
                                    <input type="checkbox" name="filter_mastery[]" value="{{ $level }}" checked>

                                    <span>{{ $level }}</span>
                                </label>
                            @endfor
                        </div>
                        <div class="filter-group">
                            <label for="lastStudiedFilter">
                                最終取り組み日
                            </label>

                            <select id="lastStudiedFilter" class="filter-select">
                                <option value="">
                                    指定しない
                                </option>

                                <option value="7">
                                    7日より前
                                </option>

                                <option value="14">
                                    14日より前
                                </option>

                                <option value="30">
                                    30日より前
                                </option>

                                <option value="60">
                                    60日より前
                                </option>

                                <option value="90">
                                    90日より前
                                </option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <p class="filter-group-label">
                                習熟度と最終取り組み日の検索方法
                            </p>

                            <label>
                                <input type="radio" name="filter_condition" value="or" checked>
                                OR検索
                            </label>

                            <label>
                                <input type="radio" name="filter_condition" value="and">
                                AND検索
                            </label>
                        </div>
                    </div>

                    <div class="filter-actions">
                        <button id="applyFilterButton" class="filter-button" type="button">
                            絞り込みを適用
                        </button>

                        <button id="resetFilterButton" class="filter-button secondary" type="button">
                            条件をリセット
                        </button>
                    </div>

                    <p id="filterMessage" class="filter-message" aria-live="polite"></p>
                </section>
                <button id="orderedModeButton" class="study-mode-button active" type="button">
                    作成順
                </button>

                <button id="shuffleModeButton" class="study-mode-button" type="button">
                    🎲 シャッフル
                </button>
            </div>
        </header>
        @if ($cards->isEmpty())
            <section class="empty-panel">
                <p>学習できるカードがありません。</p>
            </section>
        @else
            @php
                $studyCards = $cards
                    ->map(function ($card) {
                        $categoryNames = [];
                        $currentCategory = $card->category;
                        $depth = 0;

                        while ($currentCategory && $depth < 3) {
                            array_unshift($categoryNames, $currentCategory->name);

                            $currentCategory = $currentCategory->parent;
                            $depth++;
                        }

                        $categoryPath = count($categoryNames) > 0 ? implode(' ＞ ', $categoryNames) : '未設定';

                        return [
                            'id' => $card->id,
                            'front_text' => $card->front_text,
                            'front_image_url' => $card->front_image_url,
                            'back_text' => $card->back_text,
                            'back_image_url' => $card->back_image_url,
                            'memo' => $card->memo,
                            'category_id' => $card->category_id,
                            'category' => $categoryPath,
                            'mastery_level' => $card->mastery_level,
                            'last_studied_at' => $card->last_studied_at?->format('Y/m/d'),
                            'is_bookmarked' => $card->is_bookmarked,
                        ];
                    })
                    ->values();
            @endphp
            <section id="studyApp">
                <section id="filteredEmptyPanel" class="empty-panel" hidden>
                    <p>条件に合うカードがありません。</p>
                </section>

                <div id="studyContent">
                    <div class="card-scene">
                        <button id="flashcard" class="flashcard" type="button" aria-label="カードを裏返す">
                            <div class="card-face card-front">
                                <p class="side-label">表</p>

                                <p id="frontText" class="card-text"></p>

                                <img id="frontImage" class="study-card-image" alt="カード表面の画像" hidden>

                                <p class="tap-guide">
                                    タップして裏を見る
                                </p>
                            </div>

                            <div class="card-face card-back">
                                <p class="side-label">裏</p>

                                <p id="backText" class="card-text"></p>

                                <img id="backImage" class="study-card-image" alt="カード裏面の画像" hidden>

                                <p class="tap-guide">
                                    タップして表に戻る
                                </p>
                            </div>
                        </button>
                    </div>

                    <div id="imageLightbox" class="image-lightbox" hidden>
                        <img id="lightboxImage" class="lightbox-image" alt="拡大画像">

                        <p class="lightbox-guide">
                            画像をクリックすると閉じます
                        </p>
                    </div>
                    <section class="information">
                        <p id="category" class="category"></p>

                        <p id="mastery" class="mastery"></p>

                        <div class="mastery-update">
                            <p class="mastery-update-label">
                                習熟度を変更
                            </p>

                            <div id="masteryButtons" class="mastery-buttons" role="group" aria-label="習熟度を選択">
                                @for ($level = 1; $level <= 5; $level++)
                                    <button type="button" class="mastery-button"
                                        data-mastery-level="{{ $level }}">
                                        {{ $level }}
                                    </button>
                                @endfor
                            </div>

                            <p id="masteryMessage" class="mastery-message" aria-live="polite"></p>
                        </div>

                        <button id="bookmarkButton" class="bookmark-button" type="button">
                            ☆ しおりを付ける
                        </button>

                        <p id="bookmarkMessage" class="bookmark-message" aria-live="polite"></p>

                        <p id="lastStudied" class="last-studied"></p>
                        <button id="studiedButton" class="studied-button" type="button">
                            このカードに取り組んだ
                        </button>

                        <p id="studiedMessage" class="studied-message" aria-live="polite"></p>

                        <button id="memoButton" class="memo-button" type="button" hidden>
                            メモがあります
                        </button>

                        <p id="memo" class="memo"></p>
                    </section>
                    <nav class="controls">
                        <button id="previousButton" class="control-button previous-button" type="button">
                            ← 前へ
                        </button>

                        <span id="counter" class="counter"></span>

                        <button id="nextButton" class="control-button next-button" type="button">
                            次へ →
                        </button>
                    </nav>
                </div>
            </section>
        @endif
    </main>

    @if ($cards->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const originalCards = @js($studyCards);
                let cards = [...originalCards];
                const flashcard =
                    document.getElementById('flashcard');
                const frontText =
                    document.getElementById('frontText');
                const backText =
                    document.getElementById('backText');
                const frontImage =
                    document.getElementById('frontImage');

                const backImage =
                    document.getElementById('backImage');
                const imageLightbox =
                    document.getElementById('imageLightbox');

                const lightboxImage =
                    document.getElementById('lightboxImage');
                const category =
                    document.getElementById('category');
                const mastery =
                    document.getElementById('mastery');
                const lastStudied =
                    document.getElementById('lastStudied');
                const masteryButtons = document.querySelectorAll('.mastery-button');
                const masteryMessage = document.getElementById('masteryMessage');
                const studiedButton =
                    document.getElementById('studiedButton');
                const studiedMessage =
                    document.getElementById('studiedMessage');
                const memoButton =
                    document.getElementById('memoButton');
                const memo =
                    document.getElementById('memo');
                const previousButton =
                    document.getElementById('previousButton');
                const nextButton =
                    document.getElementById('nextButton');
                const counter =
                    document.getElementById('counter');
                const bookmarkButton =
                    document.getElementById('bookmarkButton');

                const bookmarkMessage =
                    document.getElementById('bookmarkMessage');
                const orderedModeButton =
                    document.getElementById('orderedModeButton');

                const shuffleModeButton =
                    document.getElementById('shuffleModeButton');

                const progress =
                    document.querySelector('.progress');

                const categoryFilter =
                    document.getElementById('categoryFilter');

                const masteryFilterInputs =
                    document.querySelectorAll(
                        'input[name="filter_mastery[]"]'
                    );

                const applyFilterButton =
                    document.getElementById('applyFilterButton');

                const resetFilterButton =
                    document.getElementById('resetFilterButton');

                const filterMessage =
                    document.getElementById('filterMessage');


                const studyContent =
                    document.getElementById('studyContent');

                const filteredEmptyPanel =
                    document.getElementById('filteredEmptyPanel');
                const lastStudiedFilter =
                    document.getElementById(
                        'lastStudiedFilter'
                    );

                const filterConditionInputs =
                    document.querySelectorAll(
                        'input[name="filter_condition"]'
                    );

                let currentIndex = getBookmarkIndex(cards);


                let currentMode = 'ordered';

                function getBookmarkIndex(cardList) {
                    const bookmarkedCard = originalCards.find(
                        (card) => Boolean(card.is_bookmarked)
                    );

                    if (!bookmarkedCard) {
                        return 0;
                    }

                    const exactBookmarkIndex = cardList.findIndex(
                        (card) =>
                        Number(card.id) === Number(bookmarkedCard.id)
                    );

                    if (exactBookmarkIndex >= 0) {
                        return exactBookmarkIndex;
                    }

                    const bookmarkedOriginalIndex =
                        originalCards.findIndex(
                            (card) =>
                            Number(card.id) ===
                            Number(bookmarkedCard.id)
                        );

                    if (bookmarkedOriginalIndex < 0) {
                        return 0;
                    }

                    for (
                        let index = bookmarkedOriginalIndex + 1; index < originalCards.length; index++
                    ) {
                        const nextCardId = originalCards[index].id;

                        const nextFilteredIndex = cardList.findIndex(
                            (card) =>
                            Number(card.id) === Number(nextCardId)
                        );

                        if (nextFilteredIndex >= 0) {
                            return nextFilteredIndex;
                        }
                    }

                    return 0;
                }

                function getSelectedMasteryLevels() {
                    return Array.from(masteryFilterInputs)
                        .filter((input) => input.checked)
                        .map((input) => Number(input.value));
                }

                function getSelectedFilterCondition() {
                    const selectedInput =
                        Array.from(filterConditionInputs)
                        .find((input) => input.checked);

                    return selectedInput?.value ?? 'or';
                }

                function matchesLastStudiedCondition(
                    card,
                    selectedDays
                ) {
                    if (!selectedDays) {
                        return false;
                    }

                    /*
                     * 一度も取り組んでいないカードも、
                     * 復習対象として含めます。
                     */
                    if (!card.last_studied_at) {
                        return true;
                    }

                    const dateParts =
                        card.last_studied_at.split('/');

                    if (dateParts.length !== 3) {
                        return false;
                    }

                    const studiedDate =
                        new Date(
                            Number(dateParts[0]),
                            Number(dateParts[1]) - 1,
                            Number(dateParts[2])
                        );

                    const thresholdDate =
                        new Date();

                    thresholdDate.setHours(
                        0,
                        0,
                        0,
                        0
                    );

                    thresholdDate.setDate(
                        thresholdDate.getDate() -
                        Number(selectedDays)
                    );

                    return studiedDate < thresholdDate;
                }

                function getSelectedCategoryIds() {
                    if (categoryFilter.value === '') {
                        return [];
                    }

                    const selectedOption =
                        categoryFilter.options[categoryFilter.selectedIndex];

                    try {
                        return JSON.parse(
                            selectedOption.dataset.categoryIds ?? '[]'
                        ).map((id) => Number(id));
                    } catch (error) {
                        console.error(
                            'カテゴリーIDの読み込みに失敗しました。',
                            error
                        );

                        return [Number(categoryFilter.value)];
                    }
                }

                function getFilteredCards() {
                    const selectedCategoryIds =
                        getSelectedCategoryIds();

                    const selectedMasteryLevels =
                        getSelectedMasteryLevels();

                    const selectedDays =
                        lastStudiedFilter.value;

                    const filterCondition =
                        getSelectedFilterCondition();

                    const usesMasteryCondition =
                        selectedMasteryLevels.length > 0;

                    const usesDateCondition =
                        selectedDays !== '';

                    return originalCards.filter((card) => {
                        const matchesCategory =
                            selectedCategoryIds.length === 0 ||
                            selectedCategoryIds.includes(
                                Number(card.category_id)
                            );

                        const matchesMastery =
                            usesMasteryCondition &&
                            selectedMasteryLevels.includes(
                                Number(card.mastery_level)
                            );

                        const matchesLastStudied =
                            usesDateCondition &&
                            matchesLastStudiedCondition(
                                card,
                                selectedDays
                            );

                        let matchesReviewCondition = true;

                        if (
                            usesMasteryCondition &&
                            usesDateCondition
                        ) {
                            matchesReviewCondition =
                                filterCondition === 'and' ?
                                matchesMastery &&
                                matchesLastStudied :
                                matchesMastery ||
                                matchesLastStudied;
                        } else if (usesMasteryCondition) {
                            matchesReviewCondition =
                                matchesMastery;
                        } else if (usesDateCondition) {
                            matchesReviewCondition =
                                matchesLastStudied;
                        }

                        return (
                            matchesCategory &&
                            matchesReviewCondition
                        );
                    });
                }

                function applyCurrentFilters() {
                    const filteredCards = getFilteredCards();

                    if (currentMode === 'shuffle') {
                        cards = shuffleCards(filteredCards);
                        currentIndex = 0;
                    } else {
                        cards = [...filteredCards];
                        currentIndex = getBookmarkIndex(cards);
                    }

                    if (cards.length === 0) {
                        filterMessage.textContent =
                            '条件に合うカードがありません。';
                    } else {
                        filterMessage.textContent =
                            `${cards.length}枚のカードに絞り込みました。`;
                    }

                    renderCard();
                }

                function shuffleCards(cardList) {
                    const shuffled = [...cardList];

                    for (let index = shuffled.length - 1; index > 0; index--) {
                        const randomIndex =
                            Math.floor(Math.random() * (index + 1));

                        [shuffled[index], shuffled[randomIndex]] = [shuffled[randomIndex], shuffled[index]];
                    }

                    return shuffled;
                }

                function renderCard() {
                    const card = cards[currentIndex];

                    if (!card) {
                        if (studyContent) {
                            studyContent.hidden = true;
                        }

                        if (filteredEmptyPanel) {
                            filteredEmptyPanel.hidden = false;
                        }

                        return;
                    }

                    if (studyContent) {
                        studyContent.hidden = false;
                    }

                    if (filteredEmptyPanel) {
                        filteredEmptyPanel.hidden = true;
                    }
                    flashcard.classList.remove('is-flipped');
                    memo.classList.remove('is-visible');

                    const hasFrontText =
                        typeof card.front_text === 'string' &&
                        card.front_text.trim() !== '';

                    frontText.textContent =
                        hasFrontText ?
                        card.front_text :
                        '';

                    frontText.hidden = !hasFrontText;

                    const hasBackText =
                        typeof card.back_text === 'string' &&
                        card.back_text.trim() !== '';

                    backText.textContent =
                        hasBackText ?
                        card.back_text :
                        '';

                    backText.hidden = !hasBackText;

                    if (card.front_image_url) {
                        frontImage.src =
                            card.front_image_url;

                        frontImage.hidden = false;
                    } else {
                        frontImage.removeAttribute('src');
                        frontImage.hidden = true;
                    }

                    if (card.back_image_url) {
                        backImage.src =
                            card.back_image_url;

                        backImage.hidden = false;
                    } else {
                        backImage.removeAttribute('src');
                        backImage.hidden = true;
                    }
                    category.textContent =
                        `カテゴリー：${card.category ?? '未設定'}`;

                    const level = Number(card.mastery_level) || 1;

                    mastery.textContent =
                        `習熟度：${'★'.repeat(level)}` +
                        `${'☆'.repeat(5 - level)}`;

                    masteryButtons.forEach((button) => {
                        const level = Number(button.dataset.masteryLevel);

                        button.classList.toggle(
                            'active',
                            level === Number(card.mastery_level)
                        );
                    });
                    const isBookmarked = Boolean(card.is_bookmarked);

                    bookmarkButton.textContent = isBookmarked ?
                        '🔖 ここから再開' :
                        'このカードを再開位置にする';
                    bookmarkButton.classList.toggle(
                        'is-bookmarked',
                        isBookmarked
                    );

                    bookmarkMessage.textContent = '';

                    lastStudied.textContent =
                        card.last_studied_at ?
                        `最終取り組み日：${card.last_studied_at}` :
                        '最終取り組み日：まだありません';

                    counter.textContent =
                        `${currentIndex + 1} / ${cards.length}`;

                    previousButton.disabled =
                        currentIndex === 0;

                    nextButton.disabled =
                        cards.length === 0;
                    const hasMemo =
                        typeof card.memo === 'string' &&
                        card.memo.trim() !== '';

                    memoButton.hidden = !hasMemo;
                    memoButton.textContent = 'メモがあります';
                    memo.textContent = hasMemo ? card.memo : '';

                    studiedMessage.textContent = '';

                }

                flashcard.addEventListener('click', () => {
                    flashcard.classList.toggle('is-flipped');
                });
                frontImage.addEventListener(
                    'click',
                    (event) => {
                        event.stopPropagation();

                        openImageLightbox(
                            frontImage
                        );
                    }
                );

                backImage.addEventListener(
                    'click',
                    (event) => {
                        event.stopPropagation();

                        openImageLightbox(
                            backImage
                        );
                    }
                );

                imageLightbox.addEventListener(
                    'click',
                    () => {
                        closeImageLightbox();
                    }
                );

                document.addEventListener(
                    'keydown',
                    (event) => {
                        if (
                            event.key === 'Escape' &&
                            !imageLightbox.hidden
                        ) {
                            closeImageLightbox();
                        }
                    }
                );
                nextButton.addEventListener(
                    'click',
                    () => {
                        if (cards.length === 0) {
                            return;
                        }

                        currentIndex =
                            currentIndex >= cards.length - 1 ?
                            0 :
                            currentIndex + 1;

                        renderCard();
                    }
                );

                memoButton.addEventListener('click', () => {
                    memo.classList.toggle('is-visible');

                    memoButton.textContent =
                        memo.classList.contains('is-visible') ?
                        'メモを閉じる' :
                        'メモがあります';
                });

                previousButton.addEventListener('click', () => {
                    if (currentIndex === 0) {
                        return;
                    }

                    currentIndex--;
                    renderCard();
                });

                studiedButton.addEventListener('click', async () => {
                    const card = cards[currentIndex];

                    studiedButton.disabled = true;
                    studiedMessage.textContent = '更新しています…';

                    try {
                        const response = await fetch(
                            `/subjects/{{ $subject->id }}/cards/${card.id}/studied`, {
                                method: 'PATCH',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': @js(csrf_token()),
                                },
                            }
                        );

                        if (!response.ok) {
                            throw new Error(
                                `HTTPエラー：${response.status}`
                            );
                        }

                        const result = await response.json();

                        card.last_studied_at =
                            result.last_studied_at;

                        lastStudied.textContent =
                            `最終取り組み日：${result.last_studied_at}`;

                        studiedMessage.textContent =
                            '取り組み日を更新しました。';
                    } catch (error) {
                        console.error(error);

                        studiedMessage.textContent =
                            '更新できませんでした。';
                    } finally {
                        studiedButton.disabled = false;
                    }
                });
                masteryButtons.forEach((button) => {
                    button.addEventListener('click', async () => {
                        const card = cards[currentIndex];
                        const masteryLevel = Number(button.dataset.masteryLevel);

                        masteryMessage.textContent = '更新中です…';

                        masteryButtons.forEach((item) => {
                            item.disabled = true;
                        });

                        try {
                            const response = await fetch(
                                `/subjects/{{ $subject->id }}/cards/${card.id}/mastery`, {
                                    method: 'PATCH',
                                    headers: {
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': @js(csrf_token()),
                                    },
                                    body: JSON.stringify({
                                        mastery_level: masteryLevel,
                                    }),
                                }
                            );

                            if (!response.ok) {
                                throw new Error('習熟度を更新できませんでした。');
                            }

                            const result = await response.json();

                            card.mastery_level = result.mastery_level;

                            mastery.textContent =
                                `習熟度：${result.mastery_level}`;

                            masteryButtons.forEach((item) => {
                                const level = Number(item.dataset.masteryLevel);

                                item.classList.toggle(
                                    'active',
                                    level === Number(result.mastery_level)
                                );
                            });

                            masteryMessage.textContent =
                                '習熟度を更新しました。';
                        } catch (error) {
                            masteryMessage.textContent =
                                '習熟度を更新できませんでした。';
                        } finally {
                            masteryButtons.forEach((item) => {
                                item.disabled = false;
                            });
                        }
                    });
                });
                bookmarkButton.addEventListener('click', async () => {
                    const card = cards[currentIndex];

                    bookmarkButton.disabled = true;
                    bookmarkMessage.textContent = '更新中です…';

                    try {
                        const response = await fetch(
                            `/subjects/{{ $subject->id }}/cards/${card.id}/bookmark`, {
                                method: 'PATCH',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': @js(csrf_token()),
                                },
                            }
                        );

                        const responseText = await response.text();

                        console.log(
                            'しおりレスポンス:',
                            response.status,
                            responseText
                        );

                        if (!response.ok) {
                            throw new Error(
                                `HTTP ${response.status}: ${responseText}`
                            );
                        }

                        const result = JSON.parse(responseText);

                        originalCards.forEach((item) => {
                            if (result.is_bookmarked) {
                                item.is_bookmarked =
                                    Number(item.id) === Number(card.id);
                            } else if (
                                Number(item.id) === Number(card.id)
                            ) {
                                item.is_bookmarked = false;
                            }
                        });

                        cards.forEach((item) => {
                            if (result.is_bookmarked) {
                                item.is_bookmarked =
                                    Number(item.id) === Number(card.id);
                            } else if (
                                Number(item.id) === Number(card.id)
                            ) {
                                item.is_bookmarked = false;
                            }
                        });

                        bookmarkButton.textContent =
                            result.is_bookmarked ?
                            '🔖 ここから再開' :
                            'このカードを再開位置にする';

                        bookmarkButton.classList.toggle(
                            'is-bookmarked',
                            Boolean(result.is_bookmarked)
                        );

                        bookmarkMessage.textContent = result.message;
                    } catch (error) {
                        console.error('しおり更新エラー:', error);

                        bookmarkMessage.textContent =
                            `しおりを更新できませんでした：${error.message}`;
                    } finally {
                        bookmarkButton.disabled = false;
                    }
                });
                orderedModeButton.addEventListener('click', () => {
                    currentMode = 'ordered';

                    cards = getFilteredCards();
                    currentIndex = getBookmarkIndex(cards);

                    orderedModeButton.classList.add('active');
                    shuffleModeButton.classList.remove('active');

                    progress.textContent =
                        currentIndex > 0 ?
                        'しおりの位置から作成順で再開します。' :
                        '作成順で学習します。';

                    renderCard();
                });
                shuffleModeButton.addEventListener('click', () => {
                    currentMode = 'shuffle';

                    cards = shuffleCards(getFilteredCards());
                    currentIndex = 0;

                    shuffleModeButton.classList.add('active');
                    orderedModeButton.classList.remove('active');

                    progress.textContent =
                        'シャッフル順で学習します。';

                    renderCard();
                });
                applyFilterButton.addEventListener('click', () => {
                    const selectedMasteryLevels =
                        getSelectedMasteryLevels();

                    const selectedDays =
                        lastStudiedFilter.value;

                    if (
                        selectedMasteryLevels.length === 0 &&
                        selectedDays === ''
                    ) {
                        filterMessage.textContent =
                            '習熟度または最終取り組み日を指定してください。';

                        return;
                    }
                    applyCurrentFilters();
                });
                resetFilterButton.addEventListener('click', () => {
                    categoryFilter.value = '';
                    lastStudiedFilter.value = '';

                    filterConditionInputs.forEach(
                        (input) => {
                            input.checked =
                                input.value === 'or';
                        }
                    );

                    masteryFilterInputs.forEach((input) => {
                        input.checked = true;
                    });

                    cards = [...originalCards];
                    currentMode = 'ordered';
                    currentIndex = getBookmarkIndex(cards);

                    orderedModeButton.classList.add('active');
                    shuffleModeButton.classList.remove('active');

                    progress.textContent =
                        currentIndex > 0 ?
                        'しおりの位置から作成順で再開します。' :
                        '作成順で学習します。';

                    filterMessage.textContent =
                        '絞り込み条件をリセットしました。';

                    renderCard();
                });

                function openImageLightbox(
                    imageElement
                ) {
                    const imageSource =
                        imageElement.getAttribute('src');

                    if (!imageSource) {
                        return;
                    }

                    lightboxImage.src =
                        imageSource;

                    imageLightbox.hidden = false;

                    document.body.style.overflow =
                        'hidden';
                }

                function closeImageLightbox() {
                    imageLightbox.hidden = true;

                    lightboxImage.removeAttribute(
                        'src'
                    );

                    document.body.style.overflow =
                        '';
                }
                renderCard();
            });
        </script>
    @endif
</body>

</html>
