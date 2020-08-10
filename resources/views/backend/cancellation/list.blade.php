@extends('backend.backend_template')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <h2>Cancellation Requests</h2>
        <div class="card">
          <div class="card-body pt-5">
              <div>
                <table class="table table-bordered table-striped table-hover">
                  <thead>
                    <tr>
                      <th width="4%"></th>
                      <th>Date Request</th>
                      <th>Order No.</th>
                      <th>Customer</th>
                      <th>Status</th>
                      <th>Details</th>
                    </tr>
                  </thead>
                  <tbody>
                  @if (count($cancellation) > 0)
                    @foreach ($cancellation as $cancel_request)
                    <tr>
                      <td>
                        @if ($cancel_request->viewed == 1)
                        <i class="fa fa-eye text-success" title="Request viewed"></i>
                        @else
                        <i class="fa fa-eye text-danger" title="Request not view"></i>
                        @endif
                      </td>
                      <td>{{$cancel_request->date_request}}</td>
                      <td>{{$cancel_request->order_number}}</td>
                      <td>{{$cancel_request->customer->first_name." ".$cancel_request->customer->last_name}}</td>
                      <td>{{$cancel_request->status}}</td>
                      <td><a href="{{route('cancellation_details', ['cancellation'=>$cancel_request->id])}}" class="btn btn-dark btn-sm">View</a></td>
                    </tr>
                    @endforeach
                  @else
                  <tr>
                    <td colspan="6" align="center">No cancellation request.</td>
                  </tr>
                  @endif
                  </tbody>
                </table>
                <div>
                  {{$cancellation->links()}}
                </div>
              </div><!-- div table -->
          </div>
        </div>
    </div>
</div>
@endsection