@extends('backend.backend_template')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 class="mt-4 mb-4">Retun Requests</h2>
          <div>
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr>
                  <th width="4%"></th>
                  <th>Date Request</th>
                  <th>Order No.</th>
                  <th>Customer</th>
                  <th>Type</th>
                  <th>Status</th>
                  <th>Details</th>
                </tr>
              </thead>
              <tbody>
               @if (count($returnRequests) > 0)
                  @foreach ($returnRequests as $returnRequest)
                  <tr>
                    <td>
                      @if ($returnRequest->viewed == 1)
                      <i class="fa fa-eye text-success" title="Request viewed"></i>
                      @else
                      <i class="fa fa-eye text-danger" title="Request not view"></i>
                      @endif
                    </td>
                    <td>{{$returnRequest->date_return_request}}</td>
                    <td>{{$returnRequest->order_number}}</td>
                    <td>{{$returnRequest->customer->first_name." ".$returnRequest->customer->last_name}}</td>
                    <td>{{$returnRequest->action}}</td>
                    <td>{{$returnRequest->status}}</td>
                    <td><a href="{{route('return_request_details', ['returnRequest'=>$returnRequest->id])}}" class="btn btn-sm btn-dark">View</a></td>
                  </tr>
                  @endforeach
               @else
               <tr>
                 <td colspan="7" align="center">No return requests.</td>
               </tr>
               @endif
              </tbody>
            </table>
          </div><!-- div table -->
          {{$returnRequests->links()}}
        
    </div>
</div>
@endsection