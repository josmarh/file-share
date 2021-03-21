<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mail Subscribers') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="alert alert-warning alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <span class="fa fa-exclamation-triangle"></span>
            <strong>Note!</strong> Subscribers added to the list with status <b>"Subscribed"</b> would receive mails when a file is uploaded.
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('status') }}
                    </div>
                @endif
                <button type="button" class="btn btn-lg creator" data-toggle="modal" data-target="#create" title="Add New Subscriber">+</button>
                <button type="button" class="btn btn-lg btn-danger deletor" id="del-btn" style="display:none"> - </button>
                <button class="btn btn-outline-primary filter-btn" style="margin-left: 20px;" id="filter">
                        Filters <span class="fa fa-filter"></span>
                </button> 
                <br><br>

                <form method="GET" action="{{ route('mail-subscribers') }}" id="filter-section">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" value="{{ request()->query('name') }}" id="name" name="name" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-300 
                                                    focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" value="{{ request()->query('email') }}" id="email" name="email" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-300 
                                                    focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-300 
                                                    focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                    <option selected>Choose Status</option>
                                    <option value="1" {{ request()->query('status') ==  1 ? 'selected' : ''}} >Subscribed</option>
                                    <option value="2" {{ request()->query('status') ==  2 ? 'selected' : ''}}>Unsubscribed</option>
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
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="ms-tb">
                            @forelse($mailSubscribers as $mailSubscriber)
                            <tr id="{{$mailSubscriber->id}}" data-id="{{$mailSubscriber->id}}">
                                <td>
                                    <input type="checkbox" class="bulk-check" value="{{$mailSubscriber->id}}">
                                </td>
                                <td>{{ $mailSubscriber->name }}</td>
                                <td>{{ $mailSubscriber->email }}</td>
                                @if ($mailSubscriber->status == 1)
                                <td><span class="badge badge-success" id="status{{$mailSubscriber->id}}" >{{ 'Subscribed' }}</span></td>
                                @else
                                <td><span class="badge badge-warning" >{{ 'Unsubscribed' }}</span></td>
                                @endif
                                
                                <td align="center">
                                    <div class="dropdown dropleft">
                                        <a href="" data-toggle="dropdown"><span class="material-icons">more_vert</span></a>

                                        <div class="dropdown-menu">

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <form method="POST" action="{{ route('mail-subscribers.status', $mailSubscriber->id) }}">
                                                        @csrf
                                                        @method('put')
                                                        <div align="center">
                                                            <input type="hidden" id="main-status{{$mailSubscriber->id}}" name="status">
                                                            
                                                            <button onclick="return confirm('Are you very sure?')" id="ms-btn{{$mailSubscriber->id}}" class="btn btn-xs dropdown-item action-btn" style="font-size:10px">
                                                            <span class="material-icons" >file_download_done</span> <br> Subscribe</button>
                                                        </div>
                                                        </form>
                                                    </div>
                                                    
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div align="center">
                                                            <button class="btn btn-sm dropdown-item edit-ms action-btn" style="font-size:10px" data-id="{{$mailSubscriber->id}}">
                                                            <span class="material-icons">mode_edit</span> <br> Edit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <p class="text-center"> No result found for query </p>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $mailSubscribers
                        ->appends(['name'=> request()->query('name'),
                                    'email'=>request()->query('email'), 
                                    'status'=>request()->query('status')])
                        ->links() 
                    }}
                </div>
            </div>
             <!-- The Modal -->
             <div class="modal fade" id="create">
                <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header bg-primary" style="color:#ffffff;">
                            <h4 class="modal-title "><b>New Subscriber</b></h4>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">

                          
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div><br />
                            @endif

                            <form method="POST" action="{{ route('mail-subscribers.store') }}" >
                                @csrf
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" id="ms-name" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-300 
                                            focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"  name="name" required />
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="ms-email" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-300 
                                            focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"  name="email" required />
                                </div>
                                <button type="submit" class="btn btn-primary float-right" id="btn">Save </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- edit modal -->
            <div class="modal fade" id="edit-modal">
                <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header bg-primary" style="color:#ffffff;">
                            <h4 class="modal-title "><b>Update Subscriber</b></h4>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body update-ms"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

<script src="{{ asset('js/emailSubscription.js') }}"></script>
<script>
$(function(){
    // get data to modify modal
    $('.edit-ms').click(function(){
        // console.log('working');
        var msId = $(this).data('id');
        $.ajax({
            url: "{{ url('/mail-subscribers/edit') }}",
            method: 'get',
            data: {
                msId: msId,
            },
            success: function(result){
                $('.update-ms').html(result);

                // Display Modal
                $('#edit-modal').modal('show');
            }
        });
    });
})
</script>
</x-app-layout>