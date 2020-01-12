<h2> Edit User</h2>
<div class="card" style="width: 100%;">
    <div class="card-body">
        <form id="edituser" method="POST">

            {{ csrf_field() }}
            <div class="form-group">
                <input type="hidden" name="e_id" value="{{$users->id}}">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="e_name"  value="{{$users->name}}" placeholder="Enter name">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="e_email"  value="{{$users->email}}" aria-describedby="emailHelp" placeholder="Enter email">
            </div>
            <div class="form-group">
                <label for="role">Roles</label>
                <select class="form-control" name="e_roles">
                    <option>Select Role</option>
                    @foreach($Roles as $role)

                        <option value="{{ $role->id }}" {{ ($users->role == $role->id) ? 'selected' : '' }}>
                            {{ $role->display_name }}
                        </option>
                        
            
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>