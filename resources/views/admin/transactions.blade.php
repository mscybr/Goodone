@extends('admin.layouts')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Transactions</span> </h4>

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
            $table_data = [];
            $table_headers = [
              "Type",
              "Order/Withdraw Id",
              "Status",
              "Date",
            ];
            
            foreach ($transactions as $item ) {
                $dt = new DateTime();

                if( $item["type"] == "order"){

                  if( $item["values"]["status"] == 2  ){

                    $table_data[] = (object)[
                      (object)["type"=> "string", "value" => "Order", "name" => "Type"],
                      (object)["type"=> "string", "value" => $item["values"]["id"], "name" => "Id"],
                      (object)["type"=> "string", "value" => "Order Completed", "name" => "Id"],
                      (object)["type"=> "string", "value" => ($item["values"]["updated_at"])->format("Y-m-d H:m:s"), "name" => "Completion Date"],
                    ];
                    $table_data[] = (object)[
                      (object)["type"=> "string", "value" => "Order", "name" => "Type"],
                      (object)["type"=> "string", "value" => $item["values"]["id"], "name" => "Id"],
                      (object)["type"=> "string", "value" => "Order Created", "name" => "Id"],
                      (object)["type"=> "string", "value" => ($item["values"]["created_at"])->format("Y-m-d H:m:s"), "name" => "Creation Date"],
                    ];
                  }else if( $item["values"]["status"] == 3 ){

                    $table_data[] = (object)[
                      (object)["type"=> "string", "value" => "Order", "name" => "Type"],
                      (object)["type"=> "string", "value" => $item["values"]["id"], "name" => "Id"],
                      (object)["type"=> "string", "value" => "Order Canceled With message: ".$item["values"]["note"], "name" => "Id"],
                      (object)["type"=> "string", "value" => ($item["values"]["updated_at"])->format("Y-m-d H:m:s"), "name" => "Cancelation Date"],
                    ];
                    $table_data[] = (object)[
                      (object)["type"=> "string", "value" => "Order", "name" => "Type"],
                      (object)["type"=> "string", "value" => $item["values"]["id"], "name" => "Id"],
                      (object)["type"=> "string", "value" => "Order Created", "name" => "Id"],
                      (object)["type"=> "string", "value" => ($item["values"]["created_at"])->format("Y-m-d H:m:s"), "name" => "Creation Date"],
                    ];
                  }else if( $item["values"]["status"] == 1 ){
                    $table_data[] = (object)[
                      (object)["type"=> "string", "value" => "Order", "name" => "Type"],
                      (object)["type"=> "string", "value" => $item["values"]["id"], "name" => "Id"],
                      (object)["type"=> "string", "value" => "Order Created", "name" => "Id"],
                      (object)["type"=> "string", "value" => ($item["values"]["created_at"])->format("Y-m-d H:m:s"), "name" => "Creation Date"],
                    ];
                  }
                }else{

                  if( $item["values"]["status"] == 1  ){

                    $table_data[] = (object)[
                      (object)["type"=> "string", "value" => "Withdrawal", "name" => "Type"],
                      (object)["type"=> "string", "value" => $item["values"]["id"], "name" => "Id"],
                      (object)["type"=> "string", "value" => "Withdrawal Completed", "name" => "Id"],
                      (object)["type"=> "string", "value" => ($item["values"]["updated_at"])->format("Y-m-d H:m:s"), "name" => "Completion Date"],
                    ];
                    $table_data[] = (object)[
                      (object)["type"=> "string", "value" => "Withdrawal", "name" => "Type"],
                      (object)["type"=> "string", "value" => $item["values"]["id"], "name" => "Id"],
                      (object)["type"=> "string", "value" => "Withdrawal Request Created", "name" => "Id"],
                      (object)["type"=> "string", "value" => ($item["values"]["created_at"])->format("Y-m-d H:m:s"), "name" => "Creation Date"],
                    ];
                  }else if( $item["values"]["status"] == 2  ){

                    $table_data[] = (object)[
                      (object)["type"=> "string", "value" => "Withdrawal", "name" => "Type"],
                      (object)["type"=> "string", "value" => $item["values"]["id"], "name" => "Id"],
                      (object)["type"=> "string", "value" => "Withdrawal Rejected", "name" => "Id"],
                      (object)["type"=> "string", "value" => ($item["values"]["updated_at"])->format("Y-m-d H:m:s"), "name" => "Rejection Date"],
                    ];
                    $table_data[] = (object)[
                      (object)["type"=> "string", "value" => "Withdrawal", "name" => "Type"],
                      (object)["type"=> "string", "value" => $item["values"]["id"], "name" => "Id"],
                      (object)["type"=> "string", "value" => "Withdrawal Request Rejected", "name" => "Id"],
                      (object)["type"=> "string", "value" => ($item["values"]["created_at"])->format("Y-m-d H:m:s"), "name" => "Creation Date"],
                    ];
                  }
                }
            }
            
        @endphp
        {{-- @include('admin.forms', ["title" => "Add a new faq", "wrapper" => $wrapper, "data" => $data, "type" => "multi_fields_card_builder", "action" => route("admin_store_faqs"), "enctype" => "multipart/form-data", "method" => "POST" ]) --}}
      </div>
      <div class="row">
        @include('admin.tables_multiple', ["tables"=> [
            ["title" => "Transactions", "headers" =>  $table_headers, "data" => $table_data],
            // ["title" => "Verified Users", "headers" =>  $verfied_headers, "data" => $verfied_table_data],
          ]]);
        {{-- @include('admin.tables', ["title" => "Users", "headers" =>  $table_headers, "data" => $table_data]); --}}
      </div>
    </div>
  </div>
@endsection