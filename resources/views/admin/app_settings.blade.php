@extends('admin.layouts')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">App Settings</span> </h4>

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
                "name" => "platform_fees",
                "type" => "number",
                "label" => "Platform Fees",
                "value" => isset($settings["platform_fees"]) ? $settings["platform_fees"] : "",
                "placeholder" => "20$",
                "note" => ""
              ],
              (object)[
                "name" => "platform_fees_percentage",
                "type" => "number",
                "label" => "Platform Fees Percentage",
                "value" => isset($settings["platform_fees_percentage"]) ? $settings["platform_fees_percentage"] : "",
                "placeholder" => "20%",
                "note" => ""
              ],

            ];
            // $table_headers = [
            //   "Region",
            //   "Amount",
            // ];
            // $table_data = [];
            // foreach ($regions as $region ) {
            //   $table_data[] = (object)[
            //     (object)["type"=> "string", "value" => $region->geolocation],
            //     (object)["type"=> "string", "value" => $region->total],
            // ];
            // }
        @endphp
        @include('admin.forms', ["title" => "Edit App Settings", "wrapper" => $wrapper, "data" => $data, "type" => "multi_fields_card_builder", "action" => route("admin_edit_app_settings"), "enctype" => "", "method" => "POST" ])

      </div>
      <div class="row">
        {{-- @include('admin.tables', ["title" => "Regions", "headers" =>  $table_headers, "data" => $table_data]); --}}
      </div>
    </div>
  </div>
@endsection