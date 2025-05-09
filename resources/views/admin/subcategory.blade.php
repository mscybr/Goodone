@extends('admin.layouts')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Subcategory</span> </h4>

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
                "name" => "name",
                "type" => "text",
                "label" => "Subcategory Name",
                "value" => old("name"),
                "placeholder" => "enter subcategory name",
                "note" => ""
          ],
           (object)[
                "name" => "category_id",
                "type" => "select",
                "label" => "category Name",
                "value" => old("name"),
                "placeholder" => "Select Category",
                "note" => "",
                "options" => $categories
              ]
            ];
            $table_headers = [
              "Subcategory",
              "Category",
              "Delete"
            ];
            $table_data = [];
            foreach ($subcategories as $subcategory ) {
              $table_data[] = (object)[
                (object)["type"=> "anchor", "value" => $subcategory->name, "color" => "success", "href"=> route("admin_edit_subcategory", $subcategory)],
                (object)["type"=> "string", "value" => $subcategory->category->name],
                (object)["type"=> "anchor", "value" => "Delete", "color" => "danger", "href"=> route("admin_delete_subcategory", $subcategory)],
            ];
            }
        @endphp
        @include('admin.forms', ["title" => "Add a new subcategory", "wrapper" => $wrapper, "data" => $data, "type" => "multi_fields_card_builder", "action" => route("admin_create_subcategory"), "enctype" => "multipart/form-data", "method" => "POST" ])
      </div>
      <div class="row">
        @include('admin.tables', ["title" => "Subcategories", "headers" =>  $table_headers, "data" => $table_data]);
      </div>
    </div>
  </div>
@endsection