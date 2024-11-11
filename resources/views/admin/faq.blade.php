@extends('admin.layouts')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Faqs</span> </h4>

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
            $wrapper = function($it){
              return $it;
            };
            $data = [
              (object)[
                "name" => "question",
                "type" => "text",
                "label" => "Question",
                "value" => old("question"),
                "placeholder" => "enter a question",
                "note" => ""
              ],
              (object)[
                "name" => "answer",
                "type" => "text",
                "label" => "Answer",
                "value" => old("answer"),
                "placeholder" => "enter an answer",
                "note" => ""
              ],
            ];
            $table_headers = [
              "Question",
              "answer",
              "Delete"
            ];
            $table_data = [];
            foreach ($faqs as $faq ) {
              $table_data[] = (object)[
                (object)["type"=> "string", "value" => $faq->question],
                (object)["type"=> "string", "value" => $faq->answer],
                (object)["type"=> "anchor", "value" => "Delete", "color" => "danger", "href"=> route("admin_delete_faqs", ["id"=>$faq->id])],
            ];
            }
        @endphp
        @include('admin.forms', ["title" => "Add a new faq", "wrapper" => $wrapper, "data" => $data, "type" => "multi_fields_card_builder", "action" => route("admin_store_faqs"), "enctype" => "multipart/form-data", "method" => "POST" ])
      </div>
      <div class="row">
        @include('admin.tables', ["title" => "Faqs", "headers" =>  $table_headers, "data" => $table_data]);
      </div>
    </div>
  </div>
@endsection