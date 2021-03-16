<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registered Users') }}
        </h2>
    </x-slot>

    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('status') }}
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-hover table-bordered ">
                        <thead class="bg-primary" style="color:#ffffff;"> 
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="ms-tb">
                            @foreach($user as $users)
                            <tr data-id="{{$users->id}}">
                                <td>{{ $users->name }}</td>
                                <td>{{ $users->email }}</td>
                                @if ( $users->role_id == 1)
                                <td><span  id="status{{$users->id}}" >{{ 'Admin' }}</span></td>
                                @else
                                <td><span  >{{ 'Basic User' }}</span></td>
                                @endif
                                
                                <td align="center">
                                    <div class="dropdown dropleft">
                                        <a href="" data-toggle="dropdown"><span class="material-icons">more_vert</span></a>

                                        <div class="dropdown-menu">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <form method="POST" action="{{ route('user.role', $users->id) }}">
                                                    @csrf
                                                    @method('put')
                                                    <div align="center">
                                                        <input type="hidden" id="main-status{{$users->id}}" name="role">
                                                        
                                                        <button onclick="return confirm('Are you very sure?')" id="ms-btn{{$users->id}}" class="btn btn-sm dropdown-item" style="font-size:10px">
                                                        <span class="material-icons" id="sub-icon{{$users->id}}">psychology</span> <br> Make Admin</button>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $user->links() }}
                </div>
            </div>

        </div>
    </div>

<script>
$(function(){
    
    // button changes
    var arr=[];

    $('#ms-tb tr').each( function (i, tr) {
        arr.push($(tr).data('id'));
    });
    // console.log(arr);
    for (var i=0; i<arr.length; i++){

        if ( $('#status'+arr[i]).text() == 'Admin' )
        {
            $('#ms-btn'+arr[i]).html('<span class="material-icons" id="sub-icon{{$users->id}}">person</span> <br> Make Basic User');
            // $('#ms-btn'+arr[i]).removeClass( "btn-outline-success" ).addClass( "btn-outline-warning" );
            $('#main-status'+arr[i]).val('2');
            

        }else{
            $('#main-status'+arr[i]).val('1');
        }
    }


});
</script>
</x-app-layout>