@extends('admin.layouts')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Ratings</span> </h4>

    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
      <!-- Basic Layout -->
      <div class="col-6 mx-auto">
        @if($errors->any())
						@foreach ($errors->all(':message') as $message)
							<div class="alert alert-danger alert-dismissible fade show w-100" role="alert"> {{ $message }}</div>
						@endforeach
					@endif
        @php
            // $wrapper = function($it){
            //   return $it;
            // };
            // $data = [
            //   (object)[
            //     "name" => "question",
            //     "type" => "text",
            //     "label" => "Question",
            //     "value" => old("question"),
            //     "placeholder" => "enter a question",
            //     "note" => ""
            //   ],
            //   (object)[
            //     "name" => "answer",
            //     "type" => "text",
            //     "label" => "Answer",
            //     "value" => old("answer"),
            //     "placeholder" => "enter an answer",
            //     "note" => ""
            //   ],
            // ];
            $table_headers = [
              "User",
              "Rating",
              "Comment",
              "Delete"
            ];
            $table_data = [];
            foreach ($services as $item ) {
              $table_data[] = (object)[
                isset($item->user) ? (object)["type"=> "anchor", "value" => $item->user->full_name, "name" => "User", "href" => route("admin_get_user", $item->user_id), "color"=>"success"] : (object)["type"=> "string", "value" => "Deleted User", "name" => "User"],
                (object)["type"=> "string", "value" => $item->rate, "name" => ""],
                (object)["type"=> "string", "value" => $item->message, "name" => ""],
                (object)["type"=> "anchor", "value" => "Delete", "name" => "Delete", "href" => route("admin_delete_rating", $item) , "color"=> "danger"],
            ];
            }
        @endphp
        {{-- @include('admin.forms', ["title" => "Add a new faq", "wrapper" => $wrapper, "data" => $data, "type" => "multi_fields_card_builder", "action" => route("admin_store_faqs"), "enctype" => "multipart/form-data", "method" => "POST" ]) --}}
      </div>
      <div class="row">
        @include('admin.tables_multiple', ["tables"=> [
            ["title" => "Ratings", "headers" =>  $table_headers, "data" => $table_data],
            // ["title" => "Verified Users", "headers" =>  $verfied_headers, "data" => $verfied_table_data],
          ]]);
        {{-- @include('admin.tables', ["title" => "Users", "headers" =>  $table_headers, "data" => $table_data]); --}}
      </div>
    </div>
  </div>
@endsection