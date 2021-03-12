<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Source') }}
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
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create"> <b>New Data Source</b> </button> <br><br>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered ">
                        <thead class="bg-primary" style="color:#ffffff;">
                            <tr>
                                <th scope="col">Data Souce</th>
                                <th scope="col">Edit</th> 
                                <th scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dataSources as $dataSource)
                            <tr>
                                <td>{{ $dataSource->name }}</td>
                                <td><a href="#" class="edit-ds" data-id="{{$dataSource->id}}">Edit</a></td>
                                <td>
                                    <form method="POST" action="{{ route('data-sources.destroy', $dataSource->id) }}">
                                    @csrf
                                    @method('delete')
                                    <button onclick="return confirm('Are you very sure?')" class="btn btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $dataSources->links() }}
                </div>
            </div>
             <!-- The Modal -->
             <div class="modal fade" id="create">
                <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header bg-primary" style="color:#ffffff;">
                            <h4 class="modal-title "><b>New Data Source</b></h4>
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

                            <form method="POST" action="{{ route('data-sources.store') }}" id="dsource">
                                @csrf
                                <div class="form-group">
                                    <label for="datasource">Data source</label>
                                    <input type="text" id="ds-field" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-300 
                                            focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"  name="datasource" required />
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
                            <h4 class="modal-title thead-dark"><b>Update Data Source</b></h4>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body update-ds"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

<script>
$(function(){

    $("#btn").click(function(){

        if( $('#ds-field').val() )
        {
            $(this).attr('disabled','disabled');
            $(this).html('<span class="spinner-grow spinner-grow-sm"></span> Saving...')

            return true;
        }else{
            return false;
        }
    });

    $('.edit-ds').click(function(){
        var sourceId = $(this).data('id');
        $.ajax({
            url: "{{ url('/data-sources/edit') }}",
            method: 'get',
            data: {
                sourceId: sourceId,
            },
            success: function(result){
                $('.update-ds').html(result);

                // Display Modal
                $('#edit-modal').modal('show');
            }
        });

    });

});
</script>
</x-app-layout>