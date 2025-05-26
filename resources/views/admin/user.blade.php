@extends('admin.layouts')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{ $user->full_name }}</span> </h4>

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
                "label" => "User Name",
                "value" => $user->full_name,
                "placeholder" => "",
                "note" => ""
              ],
              (object)[
                "name" => "password",
                "type" => "password",
                "label" => "User Password",
                "value" => "",
                "placeholder" => "",
                "note" => ""
            ],
            (object)[
                "name" => "security_check",
                "type" => "select",
                "label" => "Security check",
                "value" => $user->security_check == true  ? 1 : 0,
                "placeholder" => "Security Check Status",
                "note" => "",
                "options" => [
                    ["name" => "Checked", "value" => 1],
                    ["name" => "Unchecked", "value" => 0],
                ]
            ],
            (object)[
                "name" => "verified_liscence",
                "type" => "select",
                "label" => "Liscence Verification",
                "value" => $user->verified_liscence == true ?  1 : 0,
                "placeholder" => "Liscense Verification Status",
                "note" => "",
                "options" => [
                  ["name" => "Verified", "value" => 1],
                  ["name" => "Unverified", "value" =>0],
                ]
              ]
            ];
            // $table_headers = [
            //   "Category",
            //   "Icon",
            //   "Delete"
            // ];
            // $table_data = [];
        @endphp
        @include('admin.forms', ["title" => "Edit User", "wrapper" => $wrapper, "data" => $data, "type" => "multi_fields_card_builder", "action" => route("admin_edit_user", $user), "enctype" => "multipart/form-data", "method" => "POST" ])
      </div>
      {{-- <div class="row">
        @include('admin.tables', ["title" => "Categories", "headers" =>  $table_headers, "data" => $table_data]);
      </div> --}}
    </div>
  </div>
@endsection