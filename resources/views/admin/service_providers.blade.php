@extends('admin.layouts')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Service Providers</span> </h4>

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
              // "Username",
              "Name",
              "Transactions",
              "Services",
              "Email",
              "Phone Number",
              "Balance",
              "Total Orders",
              // "Followers",
              // "Following",
              "Activation",
              "Block",
            ];
            $table_data = [];
            foreach ($users as $item ) {
              $table_data[] = (object)[
                (object)["type"=> "anchor", "value" => $item->full_name, "name" => "username", "href" => route("admin_get_user", $item), "color"=>"success"],
                (object)["type"=> "anchor", "value" => "Transactions", "name" => "Transactions", "href" => route("admin_get_transactions", $item), "color"=>"success"],
                (object)["type"=> "anchor", "value" => "Services", "name" => "Services", "href" => route("admin_get_services", ["user_id" => $item->id]), "color"=>"success"],
                // (object)["type"=> "string", "value" => $item->full_name, "name" => "Name"],
                (object)["type"=> "string", "value" => $item->email, "name" => "Email"],
                (object)["type"=> "string", "value" => $item->phone, "name" => "Number"],
                (object)["type"=> "string", "value" => "$".$item->balance, "name" => "Orders Balance"],
                (object)["type"=> "string", "value" => "$".$item->total_orders, "name" => "Orders Balance"],
                // (object)["type"=> "string", "value" => $item->followers, "name" => "followers"],
                // (object)["type"=> "string", "value" => $item->following, "name" => "following"],
                (object)["type"=> "anchor", "value" => $item->active == true ? "Deactivate User" : "Activate User", "name" => "", "href" => $item->active ? route("admin_deactivate_user", $item) : route("admin_activate_user", $item) , "color"=> $item->active ? "danger" : "success"],
                (object)["type"=> "anchor", "value" => $item->blocked == false ? "Block User" : "Unblock User", "name" => "", "href" => $item->blocked == false ? route("admin_block_user", $item) : route("admin_unblock_user", $item) , "color"=> $item->blocked == false ? "danger" : "success"],
            ];
            }
        @endphp
        {{-- @include('admin.forms', ["title" => "Add a new faq", "wrapper" => $wrapper, "data" => $data, "type" => "multi_fields_card_builder", "action" => route("admin_store_faqs"), "enctype" => "multipart/form-data", "method" => "POST" ]) --}}
      </div>
      <div class="row">
        @include('admin.tables_multiple', ["tables"=> [
            ["title" => "Users", "headers" =>  $table_headers, "data" => $table_data],
            // ["title" => "Verified Users", "headers" =>  $verfied_headers, "data" => $verfied_table_data],
          ]]);
        {{-- @include('admin.tables', ["title" => "Users", "headers" =>  $table_headers, "data" => $table_data]); --}}
      </div>
    </div>
  </div>
@endsection