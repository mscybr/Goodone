@extends('admin.layouts')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Coupons</span> </h4>

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
                "name" => "coupon",
                "type" => "text",
                "label" => "Coupon code",
                "value" => old("coupon"),
                "placeholder" => "enter a coupon code",
                "note" => ""
            ],
              (object)[
                "name" => "max_usage",
                "type" => "number",
                "label" => "Maximum times to be used",
                "value" => old("max_usage"),
                "placeholder" => "Max Usage",
                "note" => ""
            ],
              (object)[
                "name" => "percentage",
                "type" => "number",
                "label" => "Discount",
                "value" => old("percentage"),
                "placeholder" => "20%",
                "note" => ""
            ],
            ];
            $table_headers = [
              "Coupon",
              "Max Usage",
              "Times Used",
              "Discount",
              "Delete"
            ];
            $table_data = [];
            foreach ($coupons as $coupon ) {
              $table_data[] = (object)[
                (object)["type"=> "string", "value" => $coupon->coupon],
                (object)["type"=> "string", "value" => $coupon->max_usage],
                (object)["type"=> "string", "value" => $coupon->times_used],
                (object)["type"=> "string", "value" => $coupon->percentage],
                (object)["type"=> "anchor", "value" => "Delete", "color" => "danger", "href"=> route("admin_delete_coupon", ["id"=>$coupon->id])],
            ];
            }
        @endphp
        @include('admin.forms', ["title" => "Add a new coupon", "wrapper" => $wrapper, "data" => $data, "type" => "multi_fields_card_builder", "action" => route("admin_create_coupon"), "enctype" => "multipart/form-data", "method" => "POST" ])
      </div>
      <div class="row">
        @include('admin.tables', ["title" => "Coupons", "headers" =>  $table_headers, "data" => $table_data]);
      </div>
    </div>
  </div>
@endsection