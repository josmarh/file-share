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
                <button type="button" class="btn btn-lg creator" data-toggle="modal" data-target="#create" title="Add New Data Source"> + </button>
                <button type="button" class="btn btn-lg btn-danger deletor" id="del-btn" style="display:none"> - </button> 
                
                <button class="btn btn-outline-primary filter-btn" style="margin-left: 20px;" id="filter">
                    Filters <span class="fa fa-filter"></span>
                </button>
                <br><br>

                <form method="GET" action="{{ route('data-sources') }}" id="filter-section" style="display:none">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="datasource">Data source</label>
                                <input type="text" id="search" value="{{ request()->query('search') }}" name="search" 
                                        class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-300 
                                               focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
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
                                <th scope="col">Edit</th> 
                                <th scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody id="ds-tb">
                            @forelse($dataSources as $dataSource)
                            <tr id="{{$dataSource->id}}">
                                <td>
                                    <input type="checkbox" class="bulk-check" value="{{$dataSource->id}}">
                                </td>
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
                            @empty
                            <p class="text-center"> No result found for query <strong>{{ request()->query('search') }}</strong></p>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $dataSources->appends(['search' => request()->query('search') ])->links() }}
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

<script src="{{ asset('js/dataSource.js') }}"></script>
<script>
$(function(){
    $('.edit-ds').click(function(){
        console.log('fine');
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
})
</script>
</x-app-layout>