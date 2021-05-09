@if ($errors -> any()) {{-- Anyメソッドでエラーの有無を確認 --}}
  <div class="card-text text-left alert alert-danger">
      <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li> {{$error}}</li>
          @endforeach
      </ul>
  </div>
@endif
