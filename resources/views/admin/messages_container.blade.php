@extends('admin.layouts')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Messages</span> </h4>

    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <!-- Basic Layout -->
        <div style="height: 300px;overflow-y: scroll" class="col-9 mx-auto" id="messages_holder">

        </div>
    </div>
    <div class="row" >
        <div class="col-9 mx-auto mt-3" id="input">
            <textarea name="" id="message_box" cols="30" rows="10" class="form-control"></textarea>
            <input type="submit" value="send" id="submit" class="mt-2 btn btn-success">
        </div>
    </div>
  </div>
  <script>
        let holder = document.querySelector("#messages_holder");
        let submit = document.querySelector("#submit");
        let message_box = document.querySelector("#message_box");
        let contact_id = "{!! $contact_id !!}"
        submit.onmousedown = ()=>{
            const rawResponse = fetch('{!! route("admin_send_message") !!}', {
                method: 'POST',
                headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-Token': '{{ csrf_token() }}'
                },
                body: JSON.stringify({message:message_box.value, contact_id:contact_id})
            });
            message_box.value = ""
        }
        setInterval(() => {
            fetch("{!! route("admin_support_messages", ["contact_id"=>$contact_id, "user"=>$user]) !!}")
            .then(response => {
                return response.text()
            })
            .then(html => {
                holder.innerHTML = html;
            })
            .catch(error => {
                console.error('Failed to fetch page: ', error)
            })
        }, 5000);
  </script>
@endsection