@foreach($errors->all() as $err)
    <div class="error">
        {{ $err }}
    </div>
@endforeach