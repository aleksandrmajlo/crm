@if(session()->has('mess'))
    <div class="alert alert-success">
        {{ session()->get('mess') }}
    </div>
@endif
<form method="post" action="/admin/setStatus">
    @csrf
    <div class="row">
        <div class="col-sm-5">
            <div class="form-group  ">
                <label>Date</label>
                <input type="date" name="date" class="form-control">
            </div>
        </div>

    </div>

    <button class="btn btn-primary" type="submit">Backup</button>
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
