@if(session()->has('mess'))
    <div class="alert alert-success">
        {{ session()->get('mess') }}
    </div>
@endif
<form method="post" action="/admin/setStatus">
    @csrf
    <button class="btn btn-primary" type="submit">Set status</button>
</form>