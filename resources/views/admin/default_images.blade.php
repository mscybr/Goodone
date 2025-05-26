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
                "name" => "customer_image",
                "type" => "file",
                "label" => "Customer Default Image",
                "value" => isset($customer_image) ? $customer_image : "",
                "placeholder" => "",
                "note" => ""
              ],
              (object)[
                "name" => "provider_image",
                "type" => "file",
                "label" => "Service Provider Default Image",
                "value" => isset($provider_image) ? $provider_image : "",
                "placeholder" => "",
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
        @include('admin.forms', ["title" => "Edit Default Images", "wrapper" => $wrapper, "data" => $data, "type" => "multi_fields_card_builder", "action" => route("admin_edit_default_images"), "enctype" => "multipart/form-data", "method" => "POST" ])

      </div>
      <div class="row">
        {{-- @include('admin.tables', ["title" => "Regions", "headers" =>  $table_headers, "data" => $table_data]); --}}
      </div>
    </div>
  </div>
  <script>
     var customer_image = "<?= $current_customer_image ?>";
     var provider_image = "<?= $current_provider_image ?>";

    </script>
@endsection