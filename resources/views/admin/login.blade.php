@extends('admin.layouts')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Login</span> </h4>

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
                "name" => "email",
                "type" => "email",
                "label" => "Email",
                "value" => old("email"),
                "placeholder" => "enter your email",
                "note" => ""
              ],
              (object)[
                "name" => "password",
                "type" => "password",
                "label" => "Password",
                "value" => old("password"),
                "placeholder" => "enter your password",
                "note" => ""
              ],

            ];
            // $table_headers = [
            //   "Team Name",
            //   "Team Icon",
            //   "Edit",
            //   "Members",
            //   "Delete"
            // ];
            // $table_data = [];
            // foreach ($Teams as $team ) {
            //   $table_data[] = (object)[
            //     (object)["type"=> "string", "value" => $team->teamName],
            //     (object)["type"=> "image", "value" => asset("storage/images/".$team->icon)],
            //     (object)["type"=> "anchor", "value" => "Edit", "color" => "success", "href"=> route("admin_edit_team", ["id"=>$team->id])],
            //     (object)["type"=> "anchor", "value" => "Members", "color" => "success", "href"=> route("admin_create_team_member", ["team_id"=>$team->id])],
            //     (object)["type"=> "anchor", "value" => "Delete", "color" => "danger", "href"=> route("admin_delete_team", ["id"=>$team->id])],
            // ];
            // }
        @endphp
        @include('admin.forms', ["title" => "Login", "wrapper" => $wrapper, "data" => $data, "type" => "multi_fields_card_builder", "action" => route("admin_login"), "enctype" => "", "method" => "POST" ])
      </div>
      <div class="row">
        {{-- @include('admin.tables', ["title" => "Teams", "headers" =>  $table_headers, "data" => $table_data]); --}}
      </div>
    </div>
  </div>
@endsection