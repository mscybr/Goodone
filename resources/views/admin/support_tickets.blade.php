@extends('admin.layouts')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Support</span> </h4>

    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
      <!-- Basic Layout -->
      <div class="col-6 mx-auto">
        <div class="list-group">
          @foreach ($tickets as $ticket)
          <a href="{{route("admin_support_messages_container", ["contact_id"=>$ticket->contact_id, "user"=>$ticket->username])}}" class="list-group-item list-group-item-action flex-column align-items-start ">
            <div class="d-flex justify-content-between w-100">
              <h6>User: {{ $ticket->username }} @if ($ticket->seen_by_admin==0) <span class="badge bg-danger rounded-pill">1</span> @endif</h6>
              <small>{{$ticket->created_at}}</small>
            </div>
            <p class="mb-1">
              {{$ticket->message}}
            </p>
            {{-- <small>Donec id elit non mi porta.</small> --}}
          </a>
          @endforeach

        </div>
       </div>
      <div class="row">
      </div>
    </div>
  </div>
@endsection