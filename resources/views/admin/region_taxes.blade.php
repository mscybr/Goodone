@extends('admin.layouts')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Region Taxes</span> </h4>

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
                "name" => "region",
                "type" => "text",
                "label" => "Region Name",
                "value" => old("region"),
                "placeholder" => "Enter a region name",
                "note" => ""
              ],
              (object)[
                "name" => "percentage",
                "type" => "number",
                "label" => "Tax",
                "value" => old("tax"),
                "placeholder" => "5%",
                "note" => ""
              ],
            ];
            $table_headers = [
              "Region",
              "Tax",
              "Delete"
            ];
            $table_data = [];
            foreach ($regions as $region ) {
              $table_data[] = (object)[
                (object)["type"=> "string", "value" => $region->region],
                (object)["type"=> "string", "value" => $region->percentage],
                (object)["type"=> "anchor", "value" => "Delete", "color" => "danger", "href"=> route("admin_delete_region_tax", ["id"=>$region->id])],
            ];
            }
        @endphp
        @include('admin.forms', ["title" => "Add a new region", "wrapper" => $wrapper, "data" => $data, "type" => "multi_fields_card_builder", "action" => route("admin_store_region_tax"), "enctype" => "multipart/form-data", "method" => "POST" ])
      </div>
      <div class="row">
        @include('admin.tables', ["title" => "Regional Taxes", "headers" =>  $table_headers, "data" => $table_data]);
      </div>
    </div>
  </div>
@endsection