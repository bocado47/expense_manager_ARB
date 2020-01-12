<h2> Edit Role</h2>
<div class="card" style="width: 100%;">
    <div class="card-body">
        <form id="editrole" method="POST">
            {{ csrf_field() }}
                <div class="form-group">
                  <label for="name">Display Name</label>
                  <input type="hidden" name="e_id"  value="{{$Roles->id}}" >
                  <input type="text" class="form-control" id="name" name="name"  value="{{$Roles->display_name}}"  placeholder="Enter display name">
                </div>
                <div class="form-group">
                  <label for="email">Description</label>
                  <input type="text" class="form-control" id="desc" name="desc"  value="{{$Roles->description}}"  placeholder="Enter description">
                </div>
        </form>
    </div>
</div>