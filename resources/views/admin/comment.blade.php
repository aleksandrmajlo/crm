{{--
  "ip" => "118.128.100.150"
  "port" => "7820"
  "domain" => "PC020"
  "login" => "administrator"
  "password" => "5645646541"
  "weight" => 0.5
  "comment" => array:3 [▼
    "comment" => "all comment"
    "data_sub_options" => array:3 [▼
      0 => array:2 [▼
        "serialinp" => "111"
        "text" => "rrr"
      ]
      1 => array:2 [▶]
      2 => array:2 [▶]
    ]
    "serial_number" => false




--}}

<div class="row">

    <div class="col-md-12">
        <div class="row">
            <div class="col-md-2 text-center">
                <strong>Start</strong>
            </div>
            <div class="col-md-10">
                    <pre>
                         {{$data['created_at']}}
                    </pre>
            </div>
        </div>

    </div>

    <div class="col-md-12">
        <div class="row">
            <div class="col-md-2 text-center">
                <strong>End</strong>
            </div>
            <div class="col-md-10">
                    <pre>
                         {{$data['updated_at']}}
                    </pre>
            </div>
        </div>

    </div>

    <div class="col-md-12">
        <div class="row">

            @if(isset($data['comment']) && isset($data['comment']['comment']))
                <div class="col-md-2 text-center">
                    <strong>Comment</strong>
                </div>
                <div class="col-md-10">
                    <pre>
                         {{$data['comment']['comment']}}
                    </pre>
                </div>
            @endif

            @if(isset($data['comment']) && isset($data['comment']['serial_number']))
                <div class="col-md-2 text-center">
                    <strong>Serial number</strong>
                </div>
                <div class="col-md-10">
                    <pre>
                         {{$data['comment']['serial_number']}}
                    </pre>
                </div>
            @endif

            @if(isset($data['comment']) && isset($data['comment']['select']))
                <div class="col-md-2 text-center">
                    <strong>Failed status</strong>
                </div>
                <div class="col-md-10">
                    <pre>
                         {{$failed_status[$data['comment']['select']]}}
                    </pre>
                </div>
            @endif


            @if(isset($data['comment']) && isset($data['comment']['data_sub_options']))
                <div class="col-md-2 text-center">
                    <strong>Info serial </strong>
                </div>
                <div class="col-md-10">

                    @foreach($data['comment']['data_sub_options'] as $serial  )
                        <div class="row">
                            <div class="col-md-4">
                                <pre>
                                    {{$serial['serialinp']}}
                                </pre>
                            </div>
                            <div class="col-md-8">
                                <pre>
                                    {{$serial['text']}}
                                </pre>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif


        </div>
    </div>

    <div class="col-md-12">
        <h4 class="text-center">Other info</h4>
    </div>

    <div class="col-md-12">
        <div class="row">
            <div class="col-md-2 text-center">
                <strong>IP Port</strong>
            </div>
            <div class="col-md-10">
                    <pre>
                         {{$data['ip']}}:{{$data['port']}}
                    </pre>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-2 text-center">
                <strong>Domain</strong>
            </div>
            <div class="col-md-10">
                    <pre>
                         {{$data['domain']}}
                    </pre>
            </div>
        </div>
    </div>


    <div class="col-md-12">
        <div class="row">
            <div class="col-md-2 text-center">
                <strong>Login</strong>
            </div>
            <div class="col-md-10">
                    <pre>
                         {{$data['login']}}
                    </pre>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="row">
            <div class="col-md-2 text-center">
                <strong>Password</strong>
            </div>
            <div class="col-md-10">
                    <pre>
                         {{$data['password']}}
                    </pre>
            </div>
        </div>
    </div>

</div>