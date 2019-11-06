<div class="row">
   <div class="col-md-12">
       <p class="text-info">
           Status: <b>{{$status[$order->status]}}</b>
       </p>

       @if($order->serials&&!empty($order->serials)&&$order->status=='3')
           <h2 class="text-center">Serials</h2>
           <table class="table ">
              <thead>
                  <tr>
                      <th>Serial</th>
                      <th>Link</th>
                      <th>Comment</th>
                  </tr>
              </thead>
               <tbody>
                   @foreach($order->serials as $serial)
                       <tr>
                           <td>{{$serial->serial}}</td>
                           <td>
                               @if(!empty($serial->link))
                                   <a href="{{$serial->link}}" target="_blank">{{$serial->link}}</a>
                               @endif
                           </td>
                           <td>
                               <pre>
                                   {{$serial->text}}
                               </pre>
                           </td>
                       </tr>
                   @endforeach
               </tbody>
           </table>
       @endif
       @if($order->status=='4')
           @php
              $comment=unserialize($order->comment);
           @endphp
           <p class="text-danger">
               Error:{{$errors[$comment['select']]}}
           </p>
           <p class="text-danger">
               Comment:<pre>{{$comment['comment']}}</pre>
           </p>
       @endif
   </div>
</div>