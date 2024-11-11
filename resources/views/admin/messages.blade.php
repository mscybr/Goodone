@foreach ($messages as $message)
    <a href="javascript:void(0);" class="list-group-item list-group-item-action flex-column align-items-start @if ($message->sent_by_user == 0 ) active @endif">
    <div class="d-flex justify-content-between w-100">
        <h6>@if ($message->sent_by_user == 0 ) Admin @else {{$user}} @endif</h6>
        <small>{{$message->created_at}}</small>
    </div>
    <p class="mb-1">
        {{$message->message}}
    </p>
    {{-- <small>Donec id elit non mi porta.</small> --}}
    </a>
@endforeach