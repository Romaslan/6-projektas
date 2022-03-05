@extends('layouts.app')

@section('content')
<div class="container">

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
  Create new client
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create Client</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="ajaxForm">
            <div class="form-group">
                <label for="client_name">Client Name</label>
                <input id="client_name" class="form-control" type="text" name="client.name"/>
            </div>
            <div class="form-group">
                <label for="client_surname">Client Surname</label>
                <input id="client_surname" class="form-control" type="text" name="client.surname"/>
            </div>
            <div class="form-group">
                <label for="client_description">Client Description</label>
                <input id="client_description" class="form-control" type="text" name="client.description"/>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button id="close-client-create-modal" type="button" class="btn btn-secondary" >Close with Javascript</button>
        <button id="submit-ajax-form" type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- tiesiog sena koda pasiemei viska reikia imt is bootsrap 5 bibliotekos -->

<div id="alert" class="alert alert-success d-none">
    </div>
    
        <div class="ajaxForm">
            <div class="form-group">
                <label for="client_name">Client Name</label>
                <input id="client_name" class="form-control" type="text" name="client.name"/>
            </div>
            <div class="form-group">
                <label for="client_surname">Client Surname</label>
                <input id="client_surname" class="form-control" type="text" name="client.surname"/>
            </div>
            <div class="form-group">
                <label for="client_description">Client Description</label>
                <input id="client_description" class="form-control" type="text" name="client.description"/>
            </div>
            <div class="form-group">
                <button id="submit-ajax-form" class="btn btn-primary">Save</button>
            </div>
        </div>

    <table id="clients-table" class="table table-striped">
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Surname</th>
            <th>Description</th>
        </tr>
        @foreach ($clients as $client)
        <tr>
            <td>{{$client->id}}</td>
            <td>{{$client->name}}</td>
            <td>{{$client->surname}}</td>
            <td>{{$client->description}}</td>
        </tr>
        @endforeach
    </table>
</div>

<!-- Button trigger modal -->
<!-- Button trigger modal -->



<script>

$.ajaxSetup({
    headers:{
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function(){

    $("#close-client-create-modal").click(function() {
        $("#createClientModal").modal('hide')
    })

    console.log("Jequery veikia")
    $("#submit-ajax-form").click(function(){
        let client_name;
        let client_surname;
        let client_description;

        client_name = $('#client_name').val();
        client_surname = $('#client_surname').val();
        client_description = $('#client_description').val();

        // console.log(client_name + " " + client_surname + " " + client_description);

        $.ajax({
            type: 'POST',
            url: '{{route("client.storeAjax")}}',
            data: {client_name: client_name, client_surname: client_surname, client_description: client_description},
            success: function(data) {
                // $("#alert").html(data);
                console.log(data);
                
                let html = "<tr><td>"+data.clientId+"</td><td>"+data.clientName+"</td><td>"+data.clientSurname+"</td><td>"+data.clientDescription+"</td></tr>";
                $("#clients-table").append(html);

                $("#alert").removeClass("d-none");
                $("#alert").html(data.successMessage +" " + data.clientName +" " + data.clientSurname);
            }
        });
    });
})
</script>

@endsection