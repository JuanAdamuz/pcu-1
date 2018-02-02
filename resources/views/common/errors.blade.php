@if (count($errors) > 0)
    <div class="">
        <ul>
            @foreach ($errors->all() as $error)
                <li class="red-text">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif