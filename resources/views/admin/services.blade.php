@extends('admin.layouts')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Services</span> </h4>

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
              "Owner",
              "Service",
              "Orders",
              "About",
              "Cost Per Hour",
              // "Country / City",
              "Total Orders",
              "License",
              "Delete",
            ];
            $table_data = [];
            foreach ($services as $item ) {
              $table_data[] = (object)[
                isset($item->user->id) ? (object)["type"=> "anchor", "value" => $item->user->full_name, "name" => "Service", "href" => route("admin_get_user", $item->user_id), "color"=>"success"] : (object)["type"=> "string", "value" => $item->user->full_name, "name" => "User"],
                (object)["type"=> "string", "value" => $item->service, "name" => "Service"],
                (object)["type"=> "anchor", "value" => "Orders", "name" => "Service", "href" => route("admin_get_orders",["service_id" => $item->id]), "color"=>"success"],
                (object)["type"=> "string", "value" => $item->about, "name" => "About"],
                (object)["type"=> "string", "value" => $item->cost_per_hour, "name" => "Cost Per Hour"],
                // (object)["type"=> "string", "value" => $item->country ? $item->country." / ".$item->city : $item->city, "name" => "City"],
                (object)["type"=> "string", "value" => "$".$item->total_orders, "name" => "Total Orders"],
                (object)["type"=> "image", "value" => asset("storage/images/".$item->license)],
                (object)["type"=> "anchor", "value" => "Delete", "name" => "Delete", "href" => route("admin_delete_service", $item) , "color"=> "danger"],
                (object)["type"=> "anchor", "value" => $item->active ? "Deactivate" : "Activate", "name" => $item->active ? "Deactivate" : "Activate", "href" => route("admin_toggle_service_activation", $item) , "color"=> $item->active ? "danger"  : "success"],
            ];
            }
        @endphp
        {{-- @include('admin.forms', ["title" => "Add a new faq", "wrapper" => $wrapper, "data" => $data, "type" => "multi_fields_card_builder", "action" => route("admin_store_faqs"), "enctype" => "multipart/form-data", "method" => "POST" ]) --}}
      </div>
      <div class="row">
        @include('admin.tables_multiple', ["tables"=> [
            ["title" => "Services", "headers" =>  $table_headers, "data" => $table_data],
            // ["title" => "Verified Users", "headers" =>  $verfied_headers, "data" => $verfied_table_data],
          ]]);
        {{-- @include('admin.tables', ["title" => "Users", "headers" =>  $table_headers, "data" => $table_data]); --}}
      </div>
    </div>
  </div>
@endsection