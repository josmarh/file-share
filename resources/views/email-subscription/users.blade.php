<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registered Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="card-body">
                <x-jet-validation-errors class="mb-4" />
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('status') }}
                    </div>
                @endif
                <button class="btn btn-outline-success status-btn" id="add-user" data-toggle="modal" data-target="#create"> 
                    Add User <i class="fa fa-user-plus"></i>
                </button> 
                <button type="button" class="btn btn-outline-danger " style="display:none; margin-left: 20px;" id="del-btn" > 
                    Delete  <span class="fa fa-trash"></span>
                </button>
                <button class="btn btn-outline-primary filter-btn" style="margin-left: 20px;" id="filter">
                        Filters <span class="fa fa-filter"></span>
                </button> <br><br>
                    <!-- filter form -->
                <form method="GET" action="{{ route('users') }}" id="filter-section">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" value="{{ request()->query('name') }}" id="name" name="name" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-300 
                                                    focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" value="{{ request()->query('email') }}" id="email" name="email" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-300 
                                                    focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Role</label>
                                <select name="role" id="role" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-300 
                                                    focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                    <option selected>All</option>
                                    <option value="1" {{ request()->query('role') ==  1 ? 'selected' : ''}} >Admin</option>
                                    <option value="2" {{ request()->query('role') ==  2 ? 'selected' : ''}}>Basic User</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-300 
                                                    focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                    <option selected>All</option>
                                    <option value="1" {{ request()->query('status') ==  1 ? 'selected' : ''}} >Active</option>
                                    <option value="2" {{ request()->query('status') ==  2 ? 'selected' : ''}}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn bg-primary" style="color:#ffffff;">Search</button> 
                </form><br>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered ">
                        <thead class="bg-primary" style="color:#ffffff;"> 
                            <tr>
                                <th width="10"><input type="checkbox"  id="checkall"></th>
                                <th scope="col">@sortablelink('name')</th>
                                <th scope="col">@sortablelink('email')</th>
                                <th scope="col">{{ __('Role') }}</th>
                                <th scope="col">{{ __('Status') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="ms-tb">
                            @foreach($user as $users)
                            <tr id="{{$users->id}}" data-id="{{$users->id}}">
                                <td>
                                    <input type="checkbox" class="bulk-check" value="{{$users->id}}">
                                </td>
                                <td>{{ $users->name }}</td>
                                <td>{{ $users->email }}</td>
                                @if ( $users->role_id == 1)
                                <td><span  id="status{{$users->id}}" >{{ __('Admin') }}</span></td>
                                @elseif ( $users->role_id == 2)
                                <td><span  >{{ __('Basic User') }}</span></td>
                                @endif

                                @if ( $users->active_status == 1)
                                <td><span  id="active-status{{$users->id}}" >{{ __('Active') }}</span></td>
                                @elseif ( $users->active_status == 2)
                                <td><span  >{{ __('Inactive') }}</span></td>
                                @endif
                                
                                <td align="center">
                                    <div class="dropdown dropleft">
                                        <a href="" data-toggle="dropdown"><span class="material-icons">more_vert</span></a>

                                        <div class="dropdown-menu">
                                            
                                            <form method="POST" action="{{ route('user.role', $users->id) }}" class="dropdown-item">
                                            @csrf
                                            @method('put')
                                                <div align="center">
                                                    <input type="hidden" id="main-status{{$users->id}}" name="role">
                                                    <input type="hidden" id="main2-status{{$users->id}}" name="role2">
                                                    
                                                    <button onclick="return confirm('Are you very sure?')" id="ms-btn{{$users->id}}" class="btn btn-sm " style="font-size:10px">
                                                        <span class="material-icons" id="sub-icon{{$users->id}}">psychology</span><br> Make Admin
                                                    </button>
                                                </div>
                                            </form>

                                            <form method="POST" action="{{ route('user.status', $users->id) }}" class="dropdown-item">
                                            @csrf
                                            @method('put')
                                                <div align="center">
                                                    <input type="hidden" id="active_status{{$users->id}}" name="status">
                                                    
                                                    <button  id="ss-btn{{$users->id}}" class="btn btn-sm " style="font-size:10px">
                                                        <span class="fa fa-check-square-o" style="font-size:20px" id="sub-icon-ss{{$users->id}}"></span><br>  Active
                                                    </button>
                                                </div>
                                            </form>
                                              
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $user->appends([
                            'name'=> request()->query('name'),
                            'email'=>request()->query('email'),
                            'role'=>request()->query('role'),
                            'status'=>request()->query('status')
                        ])->links() 
                    }}
                </div>
            </div>

            <!-- create user Modal -->
            <div class="modal fade" id="create">
                <div class="modal-dialog modal-dialog-scrollable modal-md modal-dialog-top">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header bg-primary" style="color:#ffffff;">
                            <h4 class="modal-title "><b>New User</b></h4>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">

                            <form method="POST" action="{{ route('users.store') }}" >
                                @csrf
                                
                                <div>
                                    <x-jet-label for="name" value="{{ __('Name') }}" />
                                    <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                                </div>

                                <div class="mt-4">
                                    <x-jet-label for="email" value="{{ __('Email') }}" />
                                    <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                                </div>

                                <div class="mt-4">
                                    <x-jet-label for="password" value="{{ __('Password') }}" />
                                    <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                                </div>

                                <div class="mt-4">
                                    <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                                    <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                                </div>

                                <div class="mt-4 ">
                                    <x-jet-label for="role" value="{{ __('Role') }}" />
                                    <select class="custom-select block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 
                                        focus:ring-opacity-50 rounded-md shadow-sm" name="role" :value="old('role')" required >
                                        <option selected>Choose Role</option>
                                        <option value="superadministrator">{{ __('Admin') }}</option>
                                        <option value="user">{{ __('Basic User') }}</option>
                                    </select>
                                </div><br>
                                <button type="submit" class="btn btn-primary float-right" id="btn">Register </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

<script src="{{ asset('js/user.js') }}"></script>
</x-app-layout>