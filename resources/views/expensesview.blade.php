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
<div class="modal fade" id="addExpenseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Expense</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="addExpense" method="POST">

                {{ csrf_field() }}
                <div class="form-group">
                    <label for="cat">Expense Category</label>
                    <select class="form-control" name="category">
                        <option>Select Category</option>
                        @foreach($Category as $cat)

                        <option value="{{ $cat->id }}">
                            {{ $cat->display_name }}
                        </option>
            
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                  <label for="amount">Amount</label>
                  <input type="number" class="form-control" id="amount" name="amount"  placeholder="Enter Amount">
                </div>
                <div class="form-group">
                  <label for="en_date">Entry Date</label>
                  <input type="date" class="form-control" id="date" name="entry_date" >
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
    <h3>Expense Management -> Expenses</h3>
    <br/>
  
    <div class="card" style="width: 100%;">
        <div class="card-body" id="header">
            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addExpenseModal"><i class="fas fa-plus"></i> Add Expense</a>
        </div>
        <div class="card-body">
            <table id="expenseTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Expense Category</th>
                        <th>Amount</th>
                        <th>Entry Date</th>
                        <th>Create At</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var APP_URL = {!! json_encode(url('/displayE')) !!}
        var role = {{ Auth::user()->role }} 
        console.log(role);
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });

        
        var table=$('#expenseTable').DataTable( {
				"processing": true,
				"serverSide": true,
				"ajax": {
					"url":"<?= route('dataExpense') ?>",
					"dataType":"json",
					"type":"POST",
					"data":{"_token":"<?= csrf_token() ?>"}
				},
				"columns":[
                    {"data":"id"},
					{"data":"category"},
					{"data":"amount"},
                    {"data":"entry_date"},
					{"data":"created_at"},
				]
			} );
    
        $('#addExpense').on('submit',function(e){
                e.preventDefault();

                $.ajax({
                    type:"GET",
                    url:"/addexpense",
                    data:$('#addExpense').serialize(),
                    success:function(response){
                        console.log(response);
                        $('#addExpenseModal').modal('hide');
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

     
     
        
        $('#expenseTable').on( 'click', 'td', function () {
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
                                      url:'/updateExpense',
                                      data:$('#editexpense').serialize(),
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
                                      url:'/deleteExpense',
                                      data:$('#editexpense').serialize(),
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
      
            

    });
</script>
@endsection
     
   