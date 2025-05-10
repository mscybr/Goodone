@extends('admin.layouts')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Orders</span> </h4>

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
            if( request()->get("user_id") !== null ){

              $table_headers = [
                "Service",
                "Order Status",
                "Total Hours",
                "Starting Time",
                "Total Price",
                "Discount",
                "Location",
              ];
              $table_data = [];
              foreach ($orders as $item ) {
                  $dt = new DateTime();

                $table_data[] = (object)[
                  (object)["type"=> "anchor", "value" => $item->service->service, "name" => "Service", "href" => route("admin_get_service", $item->service), "color"=>"success"],
                  (object)["type"=> "string", "value" => $item->status == 1 ? "Pending" : ($item->status == 2 ? "Completed" : "Canceled" ) , "name" => "Status"],
                  (object)["type"=> "string", "value" => $item->total_hours, "name" => "Total Hours"],
                  (object)["type"=> "string", "value" => $dt->setTimestamp( $item->start_at )->format("Y-m-d H:m:s"), "name" => "Starting Date"],
                  (object)["type"=> "string", "value" => $item->price, "name" => "Price"],
                  (object)["type"=> "string", "value" => $item->coupon_percentage == null ? 0 : ($item->price / (100-$item->coupon_percentage)  ) * $item->coupon_percentage, "name" => "Discount Amount"],
                  (object)["type"=> "string", "value" => $item->location, "name" => "Location"],
                  // (object)["type"=> "image", "value" => asset("storage/images/".$item->license)],
                  // (object)["type"=> "anchor", "value" => "Delete", "name" => "Delete", "href" => route("admin_delete_service", $item) , "color"=> "danger"],
              ];
              }
            }else{
               $table_headers = [
                "User",
                "Order Status",
                "Total Hours",
                "Starting Time",
                "Total Price",
                "Discount",
                "Location",
              ];
              foreach ($orders as $item ) {
                $dt = new DateTime();

                $table_data[] = (object)[
                  (object)["type"=> "anchor", "value" => $item->user->full_name, "name" => "User", "href" => route("admin_get_user", $item->user), "color"=>"success"],
                  (object)["type"=> "string", "value" => $item->status == 1 ? "Pending" : ($item->status == 2 ? "Completed" : "Canceled" ) , "name" => "Status"],
                  (object)["type"=> "string", "value" => $item->total_hours, "name" => "Total Hours"],
                  (object)["type"=> "string", "value" => $dt->setTimestamp( $item->start_at )->format("Y-m-d H:m:s"), "name" => "Starting Date"],
                  (object)["type"=> "string", "value" => $item->price, "name" => "Price"],
                  (object)["type"=> "string", "value" => $item->coupon_percentage == null ? 0 : ($item->price / (100-$item->coupon_percentage)  ) * $item->coupon_percentage, "name" => "Discount Amount"],
                  (object)["type"=> "string", "value" => $item->location, "name" => "Location"],
                  // (object)["type"=> "image", "value" => asset("storage/images/".$item->license)],
                  // (object)["type"=> "anchor", "value" => "Delete", "name" => "Delete", "href" => route("admin_delete_service", $item) , "color"=> "danger"],
                ];
              }
            }
        @endphp
        {{-- @include('admin.forms', ["title" => "Add a new faq", "wrapper" => $wrapper, "data" => $data, "type" => "multi_fields_card_builder", "action" => route("admin_store_faqs"), "enctype" => "multipart/form-data", "method" => "POST" ]) --}}
      </div>
      <div class="row">
        @include('admin.tables_multiple', ["tables"=> [
            ["title" => "Orders", "headers" =>  $table_headers, "data" => $table_data],
            // ["title" => "Verified Users", "headers" =>  $verfied_headers, "data" => $verfied_table_data],
          ]]);
        {{-- @include('admin.tables', ["title" => "Users", "headers" =>  $table_headers, "data" => $table_data]); --}}
      </div>
    </div>
  </div>
@endsection