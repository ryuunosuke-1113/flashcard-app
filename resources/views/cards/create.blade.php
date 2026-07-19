<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject->name }}のカードを追加</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 24px;
            background: #f4f4f4;
            color: #222;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
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
            transition: background 0.15s ease, color 0.15s ease, border-color 0.15s ease;
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

        .image-paste-area {
            margin-top: 12px;
            padding: 24px 16px;
            border: 2px dashed #999;
            border-radius: 10px;
            background: #fafafa;
            color: #555;
            text-align: center;
            cursor: pointer;
            outline: none;
        }

        .image-paste-area:hover,
        .image-paste-area:focus {
            border-color: #222;
            background: #f2f2f2;
            color: #222;
        }

        .image-paste-area.has-image {
            border-style: solid;
            border-color: #2e7d32;
            background: #f3fbf4;
            color: #2e7d32;
        }

        .clipboard-paste-button {
            display: block;
            width: 100%;
            margin-top: 10px;
            padding: 13px 16px;
            border: 1px solid #222;
            border-radius: 9px;
            background: #fff;
            color: #222;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
        }

        .clipboard-paste-button:hover,
        .clipboard-paste-button:focus-visible {
            background: #222;
            color: #fff;
        }

        .clipboard-paste-button:disabled {
            border-color: #aaa;
            background: #ddd;
            color: #777;
            cursor: wait;
        }

        .clipboard-paste-button[hidden] {
            display: none;
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
    <main class="container"> <a class="back-link" href="{{ route('subjects.cards.index', $subject) }}"> ← カード一覧へ戻る </a>
        <section class="panel">
            <h1>{{ $subject->name }}のカードを追加</h1> @php $groupedCategories = $categories->groupBy('parent_id'); @endphp <form id="cardForm"
                action="{{ route('subjects.cards.store', $subject) }}" method="POST" enctype="multipart/form-data">
                @csrf <div class="form-group"> <label for="category_id"> カテゴリー（任意） </label> <select id="category_id"
                        name="category_id">
                        <option value=""> カテゴリーなし </option>
                        @foreach ($groupedCategories->get(null, collect()) as $level1)
                            <option value="{{ $level1->id }}" @selected((string) old('category_id') === (string) $level1->id)>
                                {{ $level1->name }}
                            </option>
                            @foreach ($groupedCategories->get($level1->id, collect()) as $level2)
                                <option value="{{ $level2->id }}" @selected((string) old('category_id') === (string) $level2->id)> └ {{ $level2->name }}
                                </option>
                                @foreach ($groupedCategories->get($level2->id, collect()) as $level3)
                                    <option value="{{ $level3->id }}" @selected((string) old('category_id') === (string) $level3->id)>
                                        &nbsp;&nbsp;&nbsp;└ {{ $level3->name }} </option>
                                @endforeach
                            @endforeach
                        @endforeach
                    </select> @error('category_id')
                        <p class="error"> {{ $message }} </p>
                    @enderror
                </div>
                <div class="form-group"> <label for="front_text"> 表の文章 </label>
                    <textarea id="front_text" name="front_text" placeholder="例：三平方の定理の式は？">{{ old('front_text') }}</textarea> @error('front_text')
                        <p class="error"> {{ $message }} </p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="front_image">
                        表の画像（任意）
                    </label>

                    <input id="front_image" name="front_image" type="file" accept="image/*">

                    <div id="frontImagePasteArea" class="image-paste-area" tabindex="0" role="button"
                        aria-label="表画像の貼り付けボタンを表示する">

                        ここをタップすると、画像のペーストボタンが表示されます
                    </div>

                    <button id="frontClipboardButton" class="clipboard-paste-button" type="button" hidden>
                        📋 スクリーンショットをペースト
                    </button>
                    <label class="image-color-option">
                        <input id="saveFrontImageInColor" type="checkbox">
                        カラーで保存する
                    </label>

                    <p class="image-help">
                        初期設定ではモノクロで保存します。
                        ファイル選択、または専用欄への画像貼り付けに対応しています。
                    </p>

                    <p id="frontImageMessage" class="image-message" aria-live="polite"></p>

                    @error('front_image')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="back_text">
                        裏の文章
                    </label>

                    <textarea id="back_text" name="back_text" placeholder="例：a²＋b²＝c²">{{ old('back_text') }}</textarea>

                    @error('back_text')
                        <p class="error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="back_image">
                        裏の画像（任意）
                    </label>

                    <input id="back_image" name="back_image" type="file" accept="image/*">

                    <div id="backImagePasteArea" class="image-paste-area" tabindex="0" role="button"
                        aria-label="裏画像の貼り付けボタンを表示する">

                        ここをタップすると、画像のペーストボタンが表示されます
                    </div>

                    <button id="backClipboardButton" class="clipboard-paste-button" type="button" hidden>
                        📋 スクリーンショットをペースト
                    </button>
                    <label class="image-color-option">
                        <input id="saveBackImageInColor" type="checkbox">
                        カラーで保存する
                    </label>

                    <p class="image-help">
                        初期設定ではモノクロで保存します。
                        ファイル選択、または専用欄への画像貼り付けに対応しています。
                    </p>

                    <p id="backImageMessage" class="image-message" aria-live="polite"></p>

                    @error('back_image')
                        <p class="error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div class="form-group"> <label for="memo"> メモ（任意） </label>
                    <textarea class="memo-field" id="memo" name="memo" placeholder="補足事項など">{{ old('memo') }}</textarea>
                </div>
                <div class="form-group"> <span class="mastery-title"> 習熟度 </span>
                    <div class="mastery-options">
                        @for ($level = 1; $level <= 5; $level++)
                            <label class="mastery-option"> <input type="radio" name="mastery_level"
                                    value="{{ $level }}" @checked((int) old('mastery_level', 1) === $level)>
                                <span>{{ $level }}</span> </label>
                        @endfor
                    </div>
                    <p class="mastery-help"> 1：まだ覚えていない ／ 5：よく覚えている </p> @error('mastery_level')
                        <p class="error"> {{ $message }} </p>
                    @enderror
                </div> <button class="submit-button" type="submit"> 登録する </button>
            </form>
        </section>
    </main>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cardForm =
                document.getElementById('cardForm');

            if (!cardForm) {
                return;
            }

            const submitButton =
                cardForm.querySelector('button[type="submit"]');

            const maxImageSize = 1600;
            const webpQuality = 0.8;

            /*
             * 現在処理中の画像数です。
             * 表と裏を同時に処理しても、両方が終わるまで送信できません。
             */
            let processingImageCount = 0;

            function isImageFile(file) {
                return file &&
                    file.type.startsWith('image/');
            }

            function loadImage(file) {
                return new Promise((resolve, reject) => {
                    const objectUrl =
                        URL.createObjectURL(file);

                    const image = new Image();

                    image.onload = () => {
                        URL.revokeObjectURL(objectUrl);
                        resolve(image);
                    };

                    image.onerror = () => {
                        URL.revokeObjectURL(objectUrl);

                        reject(
                            new Error(
                                '画像を読み込めませんでした。'
                            )
                        );
                    };

                    image.src = objectUrl;
                });
            }

            function calculateImageSize(width, height) {
                if (
                    width <= maxImageSize &&
                    height <= maxImageSize
                ) {
                    return {
                        width,
                        height,
                    };
                }

                const scale =
                    maxImageSize /
                    Math.max(width, height);

                return {
                    width: Math.round(width * scale),
                    height: Math.round(height * scale),
                };
            }

            function convertCanvasToBlob(canvas) {
                return new Promise((resolve, reject) => {
                    canvas.toBlob(
                        (blob) => {
                            if (!blob) {
                                reject(
                                    new Error(
                                        '画像を変換できませんでした。'
                                    )
                                );

                                return;
                            }

                            resolve(blob);
                        },
                        'image/webp',
                        webpQuality
                    );
                });
            }

            function convertToMonochrome(
                context,
                width,
                height
            ) {
                const imageData =
                    context.getImageData(
                        0,
                        0,
                        width,
                        height
                    );

                const pixels = imageData.data;

                for (
                    let index = 0; index < pixels.length; index += 4
                ) {
                    const red = pixels[index];
                    const green = pixels[index + 1];
                    const blue = pixels[index + 2];

                    const gray = Math.round(
                        red * 0.299 +
                        green * 0.587 +
                        blue * 0.114
                    );

                    pixels[index] = gray;
                    pixels[index + 1] = gray;
                    pixels[index + 2] = gray;
                }

                context.putImageData(
                    imageData,
                    0,
                    0
                );
            }

            function createProcessedFile(
                blob,
                originalName,
                fallbackName
            ) {
                const baseName =
                    originalName
                    .replace(/\.[^/.]+$/, '')
                    .trim() ||
                    fallbackName;

                return new File(
                    [blob],
                    `${baseName}.webp`, {
                        type: 'image/webp',
                        lastModified: Date.now(),
                    }
                );
            }

            function setFileToInput(input, file) {
                const dataTransfer =
                    new DataTransfer();

                dataTransfer.items.add(file);
                input.files = dataTransfer.files;
            }

            function setPasteAreaText(
                pasteArea,
                text
            ) {
                /*
                 * textareaとdivのどちらでも動くようにしています。
                 */
                if (
                    pasteArea instanceof HTMLTextAreaElement
                ) {
                    pasteArea.value = text;
                } else {
                    pasteArea.textContent = text;
                }
            }

            function updateSubmitButton() {
                if (!submitButton) {
                    return;
                }

                submitButton.disabled =
                    processingImageCount > 0;
            }

            function initializeImageUploader({
                inputId,
                pasteAreaId,
                clipboardButtonId,
                messageId,
                colorCheckboxId,
                fallbackName,
                pastedNamePrefix,
            }) {
                const imageInput =
                    document.getElementById(inputId);

                const pasteArea =
                    document.getElementById(
                        pasteAreaId
                    );
                const clipboardButton =
                    document.getElementById(
                        clipboardButtonId
                    );
                const message =
                    document.getElementById(
                        messageId
                    );

                const colorCheckbox =
                    document.getElementById(
                        colorCheckboxId
                    );


                /*
                 * 該当する画像欄がないページでは、
                 * 何もせず終了します。
                 */
                if (
                    !imageInput ||
                    !pasteArea ||
                    !clipboardButton ||
                    !message ||
                    !colorCheckbox
                ) {
                    return null;
                }
                let originalImageFile = null;

                async function processImage(file) {
                    if (!isImageFile(file)) {
                        message.textContent =
                            '画像ファイルを選択してください。';

                        return;
                    }

                    processingImageCount++;
                    updateSubmitButton();

                    message.textContent =
                        '画像を変換しています…';

                    try {
                        const image =
                            await loadImage(file);

                        const size =
                            calculateImageSize(
                                image.naturalWidth,
                                image.naturalHeight
                            );

                        const canvas =
                            document.createElement(
                                'canvas'
                            );

                        canvas.width = size.width;
                        canvas.height = size.height;

                        const context =
                            canvas.getContext(
                                '2d', {
                                    willReadFrequently: true,
                                }
                            );

                        if (!context) {
                            throw new Error(
                                '画像を処理する機能を利用できません。'
                            );
                        }

                        context.drawImage(
                            image,
                            0,
                            0,
                            size.width,
                            size.height
                        );

                        if (!colorCheckbox.checked) {
                            convertToMonochrome(
                                context,
                                size.width,
                                size.height
                            );
                        }

                        const blob =
                            await convertCanvasToBlob(
                                canvas
                            );

                        const processedFile =
                            createProcessedFile(
                                blob,
                                file.name,
                                fallbackName
                            );

                        setFileToInput(
                            imageInput,
                            processedFile
                        );

                        const mode =
                            colorCheckbox.checked ?
                            'カラー' :
                            'モノクロ';

                        const fileSizeKb =
                            Math.max(
                                1,
                                Math.round(
                                    processedFile.size /
                                    1024
                                )
                            );

                        message.textContent =
                            `${mode}画像として準備しました（約${fileSizeKb}KB）。`;
                    } catch (error) {
                        console.error(error);

                        imageInput.value = '';

                        message.textContent =
                            '画像の変換に失敗しました。別の画像でお試しください。';
                    } finally {
                        processingImageCount =
                            Math.max(
                                0,
                                processingImageCount - 1
                            );

                        updateSubmitButton();
                    }
                }

                imageInput.addEventListener(
                    'change',
                    async () => {
                        const file =
                            imageInput.files[0];

                        if (!file) {
                            originalImageFile = null;

                            message.textContent = '';

                            pasteArea.classList.remove(
                                'has-image'
                            );

                            setPasteAreaText(
                                pasteArea,
                                ''
                            );

                            return;
                        }

                        originalImageFile = file;

                        pasteArea.classList.add(
                            'has-image'
                        );

                        setPasteAreaText(
                            pasteArea,
                            '画像を選択しました'
                        );

                        await processImage(
                            originalImageFile
                        );
                    }
                );

                colorCheckbox.addEventListener(
                    'change',
                    async () => {
                        if (!originalImageFile) {
                            return;
                        }

                        await processImage(
                            originalImageFile
                        );
                    }
                );

                pasteArea.addEventListener(
                    'click',
                    () => {
                        pasteArea.focus();
                        clipboardButton.hidden = false;

                        message.textContent =
                            '下の「スクリーンショットをペースト」をタップしてください。';
                    }
                );
                pasteArea.addEventListener(
                    'keydown',
                    (event) => {
                        if (
                            event.key !== 'Enter' &&
                            event.key !== ' '
                        ) {
                            return;
                        }

                        event.preventDefault();
                        clipboardButton.hidden = false;
                        clipboardButton.focus();
                    }
                );
                clipboardButton.addEventListener(
                    'click',
                    async () => {
                        if (
                            !navigator.clipboard ||
                            typeof navigator.clipboard.read !== 'function'
                        ) {
                            message.textContent =
                                'このブラウザではペーストボタンを利用できません。ファイル選択を利用してください。';

                            return;
                        }

                        clipboardButton.disabled = true;
                        message.textContent =
                            '端末に「ペースト」が表示されたらタップしてください。';

                        try {
                            const clipboardItems =
                                await navigator.clipboard.read();

                            let imageBlob = null;
                            let imageType = '';

                            for (const clipboardItem of clipboardItems) {
                                const type =
                                    clipboardItem.types.find(
                                        (itemType) =>
                                        itemType.startsWith('image/')
                                    );

                                if (!type) {
                                    continue;
                                }

                                imageBlob =
                                    await clipboardItem.getType(type);

                                imageType = type;
                                break;
                            }

                            if (!imageBlob || !imageType) {
                                message.textContent =
                                    'クリップボードに画像が見つかりませんでした。先にスクリーンショットをコピーしてください。';

                                return;
                            }

                            let extension =
                                imageType.split('/')[1] || 'png';

                            if (extension === 'jpeg') {
                                extension = 'jpg';
                            }

                            originalImageFile =
                                new File(
                                    [imageBlob],
                                    `${pastedNamePrefix}-${Date.now()}.${extension}`, {
                                        type: imageType,
                                        lastModified: Date.now(),
                                    }
                                );

                            pasteArea.classList.add(
                                'has-image'
                            );

                            setPasteAreaText(
                                pasteArea,
                                '画像を貼り付けました'
                            );

                            message.textContent =
                                '貼り付けた画像を読み込みました。';

                            await processImage(
                                originalImageFile
                            );
                        } catch (error) {
                            console.error(error);

                            if (error.name === 'NotAllowedError') {
                                message.textContent =
                                    'ペーストが許可されませんでした。もう一度ボタンをタップし、表示された「ペースト」を選んでください。';
                            } else {
                                message.textContent =
                                    '画像を貼り付けられませんでした。ファイル選択も利用できます。';
                            }
                        } finally {
                            clipboardButton.disabled = false;
                        }
                    }
                );
                pasteArea.addEventListener(
                    'paste',
                    async (event) => {
                        const clipboardItems =
                            Array.from(
                                event.clipboardData
                                ?.items ?? []
                            );

                        const imageItem =
                            clipboardItems.find(
                                (item) =>
                                item.kind ===
                                'file' &&
                                item.type.startsWith(
                                    'image/'
                                )
                            );

                        if (!imageItem) {
                            message.textContent =
                                '画像を貼り付けてください。';

                            return;
                        }

                        const pastedFile =
                            imageItem.getAsFile();

                        if (!pastedFile) {
                            message.textContent =
                                '貼り付けた画像を読み込めませんでした。';

                            return;
                        }

                        event.preventDefault();

                        const extension =
                            pastedFile.type
                            .split('/')[1] ||
                            'png';

                        originalImageFile =
                            new File(
                                [pastedFile],
                                `${pastedNamePrefix}-${Date.now()}.${extension}`, {
                                    type: pastedFile.type,
                                    lastModified: Date.now(),
                                }
                            );

                        pasteArea.classList.add(
                            'has-image'
                        );

                        setPasteAreaText(
                            pasteArea,
                            '画像を貼り付けました'
                        );

                        message.textContent =
                            '貼り付けた画像を読み込みました。';

                        await processImage(
                            originalImageFile
                        );
                    }
                );

                return {
                    getMessageElement() {
                        return message;
                    },
                };
            }

            /*
             * 表画像
             */
            const frontUploader =
                initializeImageUploader({
                    inputId: 'front_image',
                    pasteAreaId: 'frontImagePasteArea',
                    clipboardButtonId: 'frontClipboardButton',
                    messageId: 'frontImageMessage',
                    colorCheckboxId: 'saveFrontImageInColor',
                    fallbackName: 'front-image',
                    pastedNamePrefix: 'pasted-front',
                });
            /*
             * 裏画像
             */
            const backUploader =
                initializeImageUploader({
                    inputId: 'back_image',
                    pasteAreaId: 'backImagePasteArea',
                    clipboardButtonId: 'backClipboardButton',
                    messageId: 'backImageMessage',
                    colorCheckboxId: 'saveBackImageInColor',
                    fallbackName: 'back-image',
                    pastedNamePrefix: 'pasted-back',
                });
            cardForm.addEventListener(
                'submit',
                (event) => {
                    if (
                        processingImageCount === 0
                    ) {
                        return;
                    }

                    event.preventDefault();

                    const activeUploader =
                        frontUploader ??
                        backUploader;

                    const messageElement =
                        activeUploader
                        ?.getMessageElement();

                    if (messageElement) {
                        messageElement.textContent =
                            '画像の変換が終わるまでお待ちください。';
                    }
                }
            );
        });
    </script>

</body>

</html>
