@extends('layouts.app')
@section('loggedcontent')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<style>
    .container{
        max-width: 100% !important;
    }
    #header a{
        float: right;
    }
    tr td{
        text-align: center;
    }
    tr th{
        text-align: center;
    }
</style>

{{-- modal add user --}}
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="adduser" method="POST">

                {{ csrf_field() }}
                <div class="form-group">
                  <label for="name">Name</label>
                  <input type="text" class="form-control" id="name" name="name"  placeholder="Enter name">
                </div>
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="role">Roles</label>
                    <select class="form-control" name="roles">
                        <option>Select Role</option>
                        @foreach($Roles as $role)

                        <option value="{{ $role->id }}">
                            {{ $role->display_name }}
                        </option>
            
                        @endforeach
                    </select>
                </div>
            
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" >Save </button>
        </div>
            </form>
      </div>
    </div>
  </div>

{{-- modal add user end --}}
<div class="container">
    <br/>
    <h3>User Management</h3>
    <br/>
  
    <div class="card" style="width: 100%;">
        <div class="card-body" id="header">
            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal"><i class="fas fa-user-plus"></i> Add User</a>
        </div>
        <div class="card-body">
            <table id="usertable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Create At</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var APP_URL = {!! json_encode(url('/display')) !!}
        var role = {{ Auth::user()->role }} 
        console.log(role);
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });

        
        var table=$('#usertable').DataTable( {
				"processing": true,
				"serverSide": true,
				"ajax": {
					"url":"<?= route('dataProcessing') ?>",
					"dataType":"json",
					"type":"POST",
					"data":{"_token":"<?= csrf_token() ?>"}
				},
				"columns":[
                    {"data":"id"},
					{"data":"name"},
					{"data":"email"},
                    {"data":"role"},
					{"data":"created_at"},
				]
			} );
      $('#adduser').on('submit',function(e){
            e.preventDefault();

            $.ajax({
                type:"GET",
                url:"/adduser",
                data:$('#adduser').serialize(),
                success:function(response){
                    console.log(response);
                    $('#addUserModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    table.draw();
                    alert("Data Saved");
                },
                error:function(error){
                    console.log(error);
                    alert("Data Not Saved");
                }
            });
        });
      if(role == 1)
      {
        $('#usertable').on( 'click', 'td', function () {
                  var id = $(this).parents("tr").children("td:first").text();
                    // alert(id);
                  $.alert({
                       type:'blue',
                       columnClass:"col-sm-4 col-sm-offset-2",
                       title: '',
                       content: 'url:'+APP_URL+'?id='+id,
                       buttons:{
                         save:{
                            text:'Save',
                            btnClass: 'btn-green',
                            action: function(){
                                    var main=this;
                                    $.ajax({
                                      type:"POST",
                                      url:'/updateUser',
                                      data:$('#edituser').serialize(),
                                      beforeSend: function (){
                                        $(this).html('loading..');
                                      },
                                      success: function(){
                                        $.alert({
                                          type:'green',
                                          columnClass:"col-sm-4 col-sm-offset-2",
                                          title: '',
                                          content: 'Update Success',
                                        });
                                        table.draw();
                                        // location.reload();
                                      }
                                    });
                            },
                         },
                         delete:{
                            text:'Delete',
                             btnClass: 'btn-red',
                             action: function(){
                                    var main=this;
                                    $.ajax({
                                      type:"POST",
                                      url:'/deleteUser',
                                      data:$('#edituser').serialize(),
                                      beforeSend: function (){
                                        $(this).html('loading..');
                                      },
                                      success: function(){
                                        $.alert({
                                          type:'red',
                                          columnClass:"col-sm-2 col-sm-offset-2",
                                          title: '',
                                          content: 'Delete Success',
                                        });
                                        table.draw();
                                        // location.reload();
                                      }
                                    });
                            },
                         },
                         close:{
                             text:'Close',
                             btnClass: 'btn-gray'
                         },
                       }
                  });
            } );
      }else{
        $('#usertable').on( 'click', 'td', function () {
          $.alert({
                       type:'red',
                       columnClass:"col-sm-4 col-sm-offset-2",
                       title: '',
                       content: 'Only Administrator Can Edit/Delete Data',
          });
        });
      }
            

    });
</script>
@endsection
     
   