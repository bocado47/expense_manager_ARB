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

{{-- modal add category --}}
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="addcategory" method="POST">

                {{ csrf_field() }}
                <div class="form-group">
                  <label for="name">Display Name</label>
                  <input type="text" class="form-control" id="name" name="name"  placeholder="Enter display name">
                </div>
                <div class="form-group">
                  <label for="email">Description</label>
                  <input type="text" class="form-control" id="desc" name="desc"  placeholder="Enter description">
                </div>
            
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" >Save</button>
        </div>
            </form>
      </div>
    </div>
  </div>

{{-- modal add category end --}}

<div class="container">
    <br/>
    <h3>Expense Management -> Expense Categories</h3>
    <br/>

    <div class="card" style="width: 100%;">
        <div class="card-body" id="header">
            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addCategoryModal"><i class="fas fa-plus"></i> Add Category</a>
        </div>
        <div class="card-body">
            <table id="categorytable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Name</th>
                        <th>Description</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var APP_URL = {!! json_encode(url('/displayC')) !!}
        var role = {{ Auth::user()->role }} 

        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

       

        var table=$('#categorytable').DataTable( {
				"processing": true,
				"serverSide": true,
				"ajax": {
					"url":"<?= route('dataCategory') ?>",
					"dataType":"json",
					"type":"POST",
					"data":{"_token":"<?= csrf_token() ?>"}
				},
				"columns":[
                    {"data":"id"},
					{"data":"name"},
					{"data":"desc"},
				]
		} );

        $('#addcategory').on('submit',function(e){
            e.preventDefault();

            $.ajax({
                type:"GET",
                url:"/addcategory",
                data:$('#addcategory').serialize(),
                success:function(response){
                    console.log(response);
                    $('#addCategoryModal').modal('hide');
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
        $('#categorytable').on( 'click', 'td', function () {
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
                                      url:'/updateCategory',
                                      data:$('#editcategory').serialize(),
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
                                      },
                                       error:function(data){
                                            console.log(data);
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
                                      url:'/deleteCategory',
                                      data:$('#editcategory').serialize(),
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
        $('#categorytable').on( 'click', 'td', function () {
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