@extends('layouts.app')

@section('content')
<div class="container">

    <div id="alert" class="alert alert-success">
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

</div>
    <script>

        $.ajaxSetup({
            headers:{
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function(){
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
                    data: {client_name: client_name, client_surname: client_surname, client_description: client_description}
                    success: function(data) {
                        $("#alert").html(data);
                    }
                });
            });
        })
    </script>
@endsection