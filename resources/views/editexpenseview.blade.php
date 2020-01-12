<h2> Edit Expense</h2>
<div class="card" style="width: 100%;">
    <div class="card-body">
        <form id="editexpense" method="POST">
            <input type="hidden" name="e_id"  value="{{$expense->id}}" >

            {{ csrf_field() }}
            <div class="form-group">
                <label for="cat">Expense Category</label>
                <select class="form-control" name="category">
                    <option>Select Category</option>
                    @foreach($Category as $cat)

                    <option value="{{ $cat->id }}" {{ ($expense->category == $cat->id) ? 'selected' : '' }}>
                        {{ $cat->display_name }}
                    </option>
                    
        
                    @endforeach
                </select>
            </div>
            <div class="form-group">
              <label for="amount">Amount</label>
              <input type="number" class="form-control" id="amount" name="amount"  value="{{$expense->amount}}" placeholder="Enter Amount">
            </div>
            <div class="form-group">
              <label for="en_date">Entry Date</label>
              <input type="date" class="form-control" id="date" value="{{$expense->entry_date}}" name="entry_date" >
            </div>
        </form>
    </div>
</div>