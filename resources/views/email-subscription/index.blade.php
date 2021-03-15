<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mail Subscribers') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="alert alert-warning alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <span class="material-icons">warning_amber</span>
            <strong>Note!</strong> Subscribers added to the list with status "Subscribed" would receive mails when a file is uploaded.
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
                <button type="button" class="btn btn-primary btn-lg creator" data-toggle="modal" data-target="#create" title="Add New Subscriber">+</button> <br><br>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered ">
                        <thead class="bg-primary" style="color:#ffffff;"> 
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="ms-tb">
                            @foreach($mailSubscribers as $mailSubscriber)
                            <tr data-id="{{$mailSubscriber->id}}">
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
                                                    <div class="col-xs-6">
                                                        <form method="POST" action="{{ route('mail-subscribers.status', $mailSubscriber->id) }}">
                                                        @csrf
                                                        @method('put')
                                                        <div align="center">
                                                            <input type="hidden" id="main-status{{$mailSubscriber->id}}" name="status">
                                                            
                                                            <button onclick="return confirm('Are you very sure?')" id="ms-btn{{$mailSubscriber->id}}" class="btn btn-xs dropdown-item action-btn" style="font-size:10px">
                                                            <span class="material-icons" id="sub-icon{{$mailSubscriber->id}}">file_download_done</span> <br> Subscribe</button>
                                                        </div>
                                                        </form>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div align="center">
                                                            <button class="btn btn-sm dropdown-item edit-ms action-btn" style="font-size:10px" data-id="{{$mailSubscriber->id}}">
                                                            <span class="material-icons">mode_edit</span> <br> Edit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <form method="POST" action="{{ route('mail-subscribers.destroy', $mailSubscriber->id) }}">
                                                            @csrf
                                                            @method('delete')
                                                            <div align="center">
                                                                
                                                                <button onclick="return confirm('Are you very sure?')" class="btn btn-sm dropdown-item action-btn" style="font-size:10px">
                                                                <span class="material-icons">delete_sweep</span> <br> Delete</button>
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
                    {{ $mailSubscribers->links() }}
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

<script>
$(function(){
    $('[data-toggle="popover"]').popover({
        'trigger': 'focus',
        'placement': 'left',
        html:true,
    }); 

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

    // button changes
    var arr=[];

    $('#ms-tb tr').each( function (i, tr) {
        arr.push($(tr).data('id'));
    });
    // console.log(arr);
    for (var i=0; i<arr.length; i++){

        if ( $('#status'+arr[i]).text() === 'Subscribed' )
        {
            $('#ms-btn'+arr[i]).html('<span class="material-icons" id="sub-icon{{$mailSubscriber->id}}">clear</span> <br> Unsubscribe');
            // $('#ms-btn'+arr[i]).removeClass( "btn-outline-success" ).addClass( "btn-outline-warning" );
            $('#main-status'+arr[i]).val('2');
            

        }else{
            $('#main-status'+arr[i]).val('1');
        }
    }

    $("#btn").submit(function(){

        if( $('#ms-name').val() && $('#ms-email').val() )
        {
            $(this).attr('disabled','disabled');
            $(this).html('<span class="spinner-grow spinner-grow-sm"></span> Saving...')

            return true;
        }else{
            return false;
        }
    });

});
</script>
</x-app-layout>