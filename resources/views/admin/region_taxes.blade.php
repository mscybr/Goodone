@extends('admin.layouts')
@section('content')
<iframe name="dummyframe" id="dummyframe" style="display: none;"></iframe>

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
                (object)["type"=> "string", "value" => $region->region, "editable" => true, "action" => route("admin_edit_region_tax", $region), "name" =>"region" ],
                (object)["type"=> "string", "value" => $region->percentage, "editable" => true, "action" => route("admin_edit_region_tax", $region), "name" =>"percentage"],
                (object)["type"=> "anchor", "value" => "Delete", "color" => "danger", "href"=> route("admin_delete_region_tax", ["id"=>$region->id])],
            ];
            }
        @endphp
        @include('admin.forms', ["title" => "Add a new region", "wrapper" => $wrapper, "data" => $data, "type" => "multi_fields_card_builder", "action" => route("admin_store_region_tax"), "enctype" => "multipart/form-data", "method" => "POST" ])
      </div>
      <div class="row">
         @include('admin.tables_multiple', ["tables"=> [
            ["title" => "Regional Taxes", "headers" =>  $table_headers, "data" => $table_data],
            // ["title" => "Verified Users", "headers" =>  $verfied_headers, "data" => $verfied_table_data],
          ]]);
      </div>
    </div>
  </div>
  <script>
    let all_editable_props = document.querySelectorAll("td[editable='true']");
      function fire_edit_behaviour(td_element){
        console.log("Test");
      if(td_element.querySelector("form") == null){
      //   submit_form(td_element.querySelector("button"));
      // }else{
        edit_field(td_element);
      }
    }
    function submit_form( td_element_button ){
      td_element = td_element_button.parentElement;
      td_element.ondblclick = fire_edit_behaviour.bind(null, td_element);
      td_element.innerHTML = td_element.querySelector("input").value != null ? td_element.querySelector("input").value : td_element.querySelector("input").placeholder;
    }
    function edit_field( td_element){
      let action = td_element.getAttribute("action");
      let name = td_element.getAttribute("name");
      let current_value = td_element.innerHTML;
      td_element.innerHTML = formcreator(action, name, current_value);
    }
     function formcreator(action_url, name, current_value){
      let inputs = `<input class="form-control me-3" name="${name}" placeholder="${current_value}">`;
      return `<form  method="POST" action="${action_url}" target="dummyframe" class="d-flex">
        ${inputs}
       <button class="btn btn-success">Edit</button>
      </form>`
    }
    all_editable_props.forEach(element => {
      element.ondblclick = fire_edit_behaviour.bind(null, element);
    });
  
  </script>
@endsection