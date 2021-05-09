@csrf
<div class="md-form">
  <label for="">タイトル</label>
  <input type="text" name="title" class="form-control" required value="{{ $article->title ?? old('title')}}">
  {{-- Null合体演算子を使用editとcreateで共用しているためcreate側でエラーが出ることを防ぐ --}}
</div>
<div class="form-group">
    <article-tags-input
     :initial-tags='@json($tagNames ?? [])'
     :autocomplete-items='@json($allTagNames ?? [])'
    >

    </article-tags-input>
</div>

<div class="form-group">
  <label></label>
  <textarea name="body" required class="form-control" rows="16" placeholder="本文">{{ $article->body ?? old('body') }}</textarea>
</div>
