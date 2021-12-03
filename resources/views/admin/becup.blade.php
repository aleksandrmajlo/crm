@if(session()->has('mess'))
    <div class="alert alert-success">
        {{ session()->get('mess') }}
    </div>
@endif

<form method="post" action="/admin/becup">
    @csrf
    <button class="btn btn-primary" type="submit">Becup</button>
</form>

@if($becups)
    <h2>Becup Mysql</h2>
    <ul>
        @foreach($becups as $becup)
            <li>
                <a href="{{asset('storage/'.$becup)}}" target="_blank">{{basename($becup)}}</a>
            </li>
        @endforeach
    </ul>
@endif

