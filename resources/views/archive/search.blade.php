<h2 class="text-center">
    Search by ID
</h2>
<div class="row justify-content-center mb-5">
    <div class=" col col-md-8">
        <form method="get" action="/archives">
            <div class="input-group mb-2">
                <input type="search" name="id" class="form-control " value="{{app('request')->input('id')}}"
                       placeholder="Search by ID">
            </div>
            <button type="submit" class="btn-primary btn">Submit</button>
            @if(request()->has('id'))
                <a class="btn btn-outline-info" href="/archives">Reset</a>
            @endif
        </form>
    </div>
</div>
<h2 class="text-center">
  Search   by Serial
</h2>
<div class="row justify-content-center mb-5">
    <div class=" col col-md-8">
        <form method="get" action="/archives">
            <div class="input-group mb-2">
                <input type="search" name="serial" class="form-control " value="{{app('request')->input('serial')}}"
                       placeholder="Search by Serial">
            </div>
            <button type="submit" class="btn-primary btn">Submit</button>
            @if(request()->has('serial'))
                <a class="btn btn-outline-info" href="/archives">Reset</a>
            @endif
        </form>
    </div>
</div>
