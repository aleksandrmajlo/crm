<div class="row">
    <div class="col-md-12">
        <h2 class="text-center">Filter by date:</h2>
    </div>
    <div class="col-md-12">
        <ul class="list-inline">
            @foreach($dates as $key =>$date)
                <li class="list-inline-item  @if( request()->get('date')== $key)
                                               active
                                             @endif  ">
                    <a  href="{{ route('filter_tasks')}}?date={{$key}}">{{$key}}-({{count($date)}})</a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
