@csrf

<div class="form-group">
    <label for="category_id">
        カテゴリー（任意）
    </label>

    <select id="category_id" name="category_id">
        <option value="">カテゴリーなし</option>

        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $card->category_id ?? '') == $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    @error('category_id')
        <p class="error">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="front_text">表の文章</label>

    <textarea id="front_text" name="front_text" rows="7" maxlength="10000">{{ old('front_text', $card->front_text ?? '') }}</textarea>

    @error('front_text')
        <p class="error">{{ $message }}</p>
    @enderror
</div>
<div class="form-group">
    <label for="back_text">裏の文章</label>

    <textarea id="back_text" name="back_text" rows="7" maxlength="10000" required>{{ old('back_text', $card->back_text ?? '') }}</textarea>

    @error('back_text')
        <p class="error">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="memo">メモ（任意）</label>

    <textarea id="memo" name="memo" rows="5" maxlength="10000">{{ old('memo', $card->memo ?? '') }}</textarea>

    @error('memo')
        <p class="error">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="mastery_level">習熟度</label>

    <select id="mastery_level" name="mastery_level" required>
        @for ($level = 1; $level <= 5; $level++)
            <option value="{{ $level }}" @selected(old('mastery_level', $card->mastery_level ?? 1) == $level)>
                {{ $level }}
            </option>
        @endfor
    </select>

    @error('mastery_level')
        <p class="error">{{ $message }}</p>
    @enderror
</div>

<button type="submit">
    {{ $submitLabel }}
</button>
