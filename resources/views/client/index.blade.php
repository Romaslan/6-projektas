@extends('layouts.app')

@section('content')

<style>
th div{
    cursor: pointer;
}
</style>
<div class="container">
    
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createClientModal">
        Create client
    </button>

    <input id="hidden-sort" type="hidden" value="id"/>
    <input id="hidden-direction" type="hidden" value="asc"/>

    <div id="alert" class="alert alert-success d-none">
    </div>
    
    <!-- paieska -->
    <div class="seachAjaxForm">
    <input id="searchValue" type="text">
    <span class="search-feedback"></span>
    <!-- <button type="button" id="submitSearch">Find</button> -->
    </div>

    <table id="clients-table" class="table table-striped">
        <thead>
            <tr>
                <th><div class="clients-sort" data-sort="id" data-direction="asc">ID</div></th>
                <th><div class="clients-sort"  data-sort="name" data-direction="asc">Name</div></th>
                <th><div class="clients-sort"  data-sort="surname" data-direction="asc">Surname</div></th>
                <th><div class="clients-sort"  data-sort="description" data-direction="asc">Description</div></th>
                <th><div class="clients-sort"  data-sort="clientCompany.title" data-direction="asc">Company</div></th>
                <th>Actions</th>
            </tr>
        </thead>
        <body>
            @foreach ($clients as $client)
            <tr class="client{{$client->id}}">
                <td class="col-client-id">{{$client->id}}</td>
                <td class="col-client-name">{{$client->name}}</td>
                <td class="col-client-surname">{{$client->surname}}</td>
                <td class="col-client-description">{{$client->description}}</td>
                <td class="col-client-company">{{$client->clientCompany->title}}</td>

                <td>
                    <!-- <form action={{route("client.destroy",[$client])}} method="POST"> -->
                    
                    <button class="btn btn-danger delete-client" type="submit" data-clientid="{{$client->id}}">Delete</button>
                    <button type="button" class="btn btn-primary show-client" data-bs-toggle="modal" data-bs-target="#showClientModal" data-clientid="{{$client->id}}">Show</button>
                    <button type="button" class="btn btn-secondary edit-client" data-bs-toggle="modal" data-bs-target="#editClientModal" data-clientid="{{$client->id}}">Edit</button>

                    <!-- </form> -->
                
                
                </td>
            </tr>
            @endforeach
        </body>
    </table>

    <table class="template d-none">
        <tr>
            <td class="col-client-id"></td>
            <td class="col-client-name"></td>
            <td class="col-client-surname"></td>
            <td class="col-client-description"></td>
            <td class="col-client-company"></td>
            <td>
                <button class="btn btn-danger delete-client" type="submit" data-clientid="">Delete</button>
                <button type="button" class="btn btn-primary show-client" data-bs-toggle="modal" data-bs-target="#showClientModal" data-clientid="">Show</button>
                <button type="button" class="btn btn-secondary edit-client" data-bs-toggle="modal" data-bs-target="#editClientModal" data-clientid="">Edit</button>
            </td>
        </tr>
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

// $(document).ready(function(){

    

    


$(document).ready(function() {
    $("#remove-table").click(function(){
        $('.client53').remove();
    });

    function createRow(clientId, clientName, clientSurname, clientDescription) {
                let html
                html+="<tr class='client"+data.clientid+"'>";
                html+="<td>"+clientId+"</td>";
                html+="<td>"+clientName+"</td>";
                html+="<td>"+clientSurname+"</td>";
                html+="<td>"+clientDescription+"</td>";
                html+="<td>";
                html+="<button class='btn btn-danger delete-client' type='submit' data-clientid='"+clientId+"'>Delete</button>";
                html+="</td>";
                html+="</tr>"

                return html
    }
    function createRowFromHtml(clientId, clientName, clientSurname, clientDescription, clientCompanyId) {
        $(".template tr").removeAttr("class");
        $(".template tr").addClass("client"+clientId);
        $(".template .delete-client").attr('data.clientId', clientId);
        $(".template .show-client").attr('data.clientId', clientId);
        $(".template .edit-client").attr('data.clientId', clientId);
        $(".template .col-client-id").html(clientId);
        $(".template .col-client-name").html(clientName);
        $(".template .col-client-surname").html(clientSurname);
        $(".template .col-client-description").html(clientDescription);
        $(".template .col-client-company").html(clientCompanyId);





        // console.log($(".template tbody").html());
        return $(".template tbody").html();
    }
    

    console.log("Jequery veikia")
    $("#submit-ajax-form").click(function() {
        let client_name;
        let client_surname;
        let client_description;
        let client_company_id;
        let sort;
        let direction;

        client_name = $('#client_name').val();
        client_surname = $('#client_surname').val();
        client_description = $('#client_description').val();
        client_company_id = $('#client_company_id').val();
        sort = $('#hidden-sort').val();
        direction = $('#hidden-direction').val();


        // console.log(client_name + " " + client_surname + " " + client_description);

        $.ajax({
            type: 'POST',
            url: '{{route("client.storeAjax")}}',
            data: {client_name: client_name, client_surname: client_surname, client_description: client_description, client_company_id: client_company_id, sort: sort, direction: direction},
            success: function(data) {
                // $("#alert").html(data);
                console.log(data);

                $("#clients-table tbody").html('');
                $.each(data.clients, function(key, client) {
                    let html;
                    html = createRowFromHtml(client.id, client.name, client.surname, client.description, client.client_company.title);
                    // console.log(html)
                    $("#clients-table tbody").append(html);
                });

                // let html;

                // let html = "<tr class='client"+data.clientid+"'><td>"+data.clientId+"</td><td>"+data.clientName+"</td><td>"+data.clientSurname+"</td><td>"+data.clientDescription+"</td><td><button class='btn btn-danger delete-client' type='submit' data-clientid='"+data.clientId+"'>Delete</button><button type='button' class='btn btn-primary show-client' data-bs-toggle='modal' data-bs-target='#showClientModal' data-clientid='"+data.clientId+"'>Show</button></td></tr>";
                // let html = "<tr class='client"+data.clientId+"'><td>"+data.clientId+"</td><td>"+data.clientName+"</td><td>"+data.clientSurname+"</td><td>"+data.clientDescription+"</td><td><button class='btn btn-danger delete-client' type='submit' data-clientid='"+data.clientId+"'>DELETE</button><button type='button' class='btn btn-primary show-client' data-bs-toggle='modal' data-bs-target='#showClientModal' data-clientid='"+data.clientId+"'>Show</button><button type='button' class='btn btn-secondary edit-client' data-bs-toggle='modal' data-bs-target='#editClientModal' data-clientid='"+data.clientId+"'>Edit</button></td></tr>";

                // html = createRow(data.clientId, data.clientName, data.clientSurname, data.clientDescription);

                html = createRowFromHtml(data.clientId, data.clientName, data.clientSurname, data.clientDescription, data.clientCompanyTitle);
                $("#clients-table").append(html);

                $("#createClientModal").hide()
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('body').css({overflow:'auto'});

                $("#alert").removeClass("d-none");
                $("#alert").html(data.successMessage +" " + data.clientName +" " + data.clientSurname);

                $('#client_name').val('');
                $('#client_surname').val('');
                $('#client_description').val('');
            }
        });
    });

    // $(".delete-client").click(function(){
        $(document).on('click','.delete-client', function() {

            let clientid;
            clientid = $(this).attr('data-clientid');
            console.log(clientid);

            $.ajax({
            type: 'POST',
            url: '/clients/deleteAjax/' + clientid,

            success: function(data) {
                // $("#alert").html(data);
                console.log(data);

                $('.client'+clientid).remove();
                $("#alert").removeClass("d-none");
                $("#alert").html(data.successMessage);
            }
        });

    });

        $(document).on('click','.show-client', function() {

            let clientid;
            clientid = $(this).attr('data-clientid');
            console.log(clientid);

            $.ajax({
            type: 'GET',
            url: '/clients/showAjax/' + clientid,

            success: function(data) {
                // $("#alert").html(data);
                $('.show-client-id').html(data.clientId);
                $('.show-client-name').html(data.clientName);
                $('.show-client-surname').html(data.clientSurname);
                $('.show-client-description').html(data.clientDescription);
                $('.show-client-company').html(data.clientCompanyTitle);

            }
        });
    });

    $(document).on('click','.edit-client', function() {

        let clientid;
            clientid = $(this).attr('data-clientid');
            console.log(clientid);

            $.ajax({
            type: 'GET',
            url: '/clients/showAjax/' + clientid,

            success: function(data) {
                // $("#alert").html(data);
                $('#edit_client_id').val(data.clientId);
                $('#edit_client_name').val(data.clientName);
                $('#edit_client_surname').val(data.clientSurname);
                $('#edit_client_description').val(data.clientDescription);

                $('#edit_client_company_id option').removeAttr('selected');
                $('#edit_client_company_id').val(data.clientCompanyId);
                $('#edit_client_company_id ,company'+ data.clientCompanyId).attr("selected", "selected");
            }
        });
    });  
    
    $(document).on('click','.update-client', function() {
        let clientid;
        let client_name;
        let client_surname;
        let client_description;
        let client_company_id;


        clientid = $('#edit_client_id').val();
        client_name = $('#edit_client_name').val();
        client_surname = $('#edit_client_surname').val();
        client_description = $('#edit_client_description').val();
        client_company_id = $('#edit_client_company_id').val();

        $.ajax({
            type: 'POST',
            url: '/clients/updateAjax/' + clientid,
            data: {client_name: client_name, client_surname: client_surname, client_description: client_description, client_company_id: client_company_id},
            success: function(data) {
                // $("#alert").html(data);
                // $('#edit-client-id').html(data.clientId);
                // $(".client"+clientid+ " " + ".col-client-id").html(data.clientId)
                $(".client"+clientid+ " " + ".col-client-name").html(data.clientName);
                $(".client"+clientid+ " " + ".col-client-surname").html(data.clientSurname);
                $(".client"+clientid+ " " + ".col-client-description").html(data.clientDescription);
                $(".client"+clientid+ " " + ".col-client-company").html(data.clientCompanyTitle);


                    $("#alert").removeClass("d-none");
                    $("#alert").html(data.successMessage);

                    $("#editClientModal").hide()
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    $('body').css({overflow:'auto'});
            }
        });
    })
    $('.clients-sort').click(function(){
        let sort;
        let direction;

        sort = $(this).attr('data-sort');
        direction = $(this).attr('data-direction');

        $("#hidden-sort").val(sort);
        $("#hidden-direction").val(direction);

        if(direction == 'asc') {
            $(this).attr('data-direction', 'desc');
        } else {
            $(this).attr('data-direction', 'asc');
        }

        $.ajax({
            type: 'GET',
            url: '{{route("client.indexAjax")}}',
            data: {sort:sort, direction: direction},
            success: function(data) {
                console.log(data.clients);
               
                $("#clients-table tbody").html('');
                $.each(data.clients, function(key, client) {
                    
                    let html;
                    
                    html = createRowFromHtml(client.id, client.name, client.surname, client.description, client.client_company.title);
                    // console.log(html)
                    $("#clients-table tbody").append(html);
                });
            }
        });
    })

    // $('#submitSearch').click(function(){
        $(document).on('input', '#searchValue', function() {
        let searchValue = $('#searchValue').val();
        let searchFieldCount = searchValue.length;

        if(searchFieldCount == 0) {
            console.log("Field is empty")
            $(".search-feedback").css('display', 'block');
            $(".search-feedback").html("Field is empty");
        }else if (searchFieldCount != 0 && searchFieldCount < 3 ) {
            console.log("Min 3")
            $(".search-feedback").css('display', 'block');
            $(".search-feedback").html("Min 3");
        }else {
            $(".search-feedback").css('display', 'none');


        console.log(searchFieldCount);

        console.log(searchValue);

        $.ajax({
            type: 'GET',
            url: '{{route("client.searchAjax")}}',
            data: {searchValue: searchValue},

            success: function(data) {
                
                console.log(data)
                if($.isEmptyObject(data.errorMessage)) {
                    $("#clients-table").show();
                    $('#alert').addClass('d-none');
                    $("#clients-table tbody").html('');
                $.each(data.clients, function(key, client) {
                    
                    let html;
                    
                    html = createRowFromHtml(client.id, client.name, client.surname, client.description, client.company_id);
                    // console.log(html)
                    $("#clients-table tbody").append(html);
                });
                } else {
                        $("#clients-table").hide();
                        $('#alert').removeClass('alert-success');
                        $('#alert').addClass('alert-danger');
                        $('#alert').removeClass('d-none');
                        $("#alert").html(data.errorMessage);
                }
            }
        });
        }
    });
})
</script>

@endsection