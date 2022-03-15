@extends('layouts.app')

@section('content')
<div class="container">
    <div id="alert" class="alert d-none">
    </div>
    <table class="table table-striped">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        @foreach ($companies as $company)
        <tr class="company{{$company->id}}">
            <td>{{$company->id}}</td>
            <td>{{$company->title}}</td>
            <td>{{$company->description}}</td>
            <td>
                <button class="btn btn-danger delete-company" type="submit" data-companyid="{{$company->id}}">Delete</button>
            </td>
        </tr>
        @endforeach
    </table>
</div>
<script>
    $.ajaxSetup({
        headers:{
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function() {
        $(document).on('click','.delete-company', function() {

            let companyid;
            companyid = $(this).attr('data-companyid');
            console.log(companyid);

            $.ajax({
            type: 'POST',
            url: '/companies/deleteAjax/' + companyid,

            success: function(data) {
                // $("#alert").html(data);
                console.log(data);

                // $('.company'+companyid).remove();
                // $("#alert").removeClass("d-none");
                // $("#alert").html(data.successMessage);
                    if($.isEmptyObject(data.errorMessage)) {
                        $('#alert').removeClass('alert-danger');
                        $('#alert').addClass('alert-success');
                        $('#alert').removeClass('d-none');
                        $('.company'+companyid).remove();
                        $("#alert").html(data.successMessage);

                    } else {
                        $('#alert').removeClass('alert-success');
                        $('#alert').addClass('alert-danger');
                        $('#alert').removeClass('d-none');
                        $("#alert").html(data.errorMessage);
                    }
                }
            });
        });
    });
</script>

@endsection