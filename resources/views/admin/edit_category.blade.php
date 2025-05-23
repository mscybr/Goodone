@extends('admin.layouts')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Edit Category</span> </h4>

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
                "name" => "category_name",
                "type" => "text",
                "label" => "Category Name",
                "value" => old("category_name"),
                "placeholder" => "enter category name",
                "note" => ""
              ],
              (object)[
                "name" => "image",
                "type" => "file",
                "label" => "image",
                "value" => old("image"),
                "placeholder" => "",
                "note" => ""
              ],
            ];
            $table_headers = [
              "Category",
              "Icon",
              "Delete"
            ];
            $table_data = [];
              $table_data[] = (object)[
                (object)["type"=> "string", "value" => $category->name],
                (object)["type"=> "image", "value" => asset("storage/images/".$category->image)],
                (object)["type"=> "anchor", "value" => "Delete", "color" => "danger", "href"=> route("admin_delete_category", $category)],
            ];
        @endphp
        @include('admin.forms', ["title" => "Edit Category", "wrapper" => $wrapper, "data" => $data, "type" => "multi_fields_card_builder", "action" => route("admin_update_category", $category), "enctype" => "multipart/form-data", "method" => "POST" ])
      </div>
      <div class="row">
        {{-- @include('admin.tables', ["title" => "Categories", "headers" =>  $table_headers, "data" => $table_data]); --}}
      </div>
    </div>
  </div>
@endsection