<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                 
                <div class="container">
                <div class="card-deck" style="margin-top: 15px;">
                
                    <div class="card bg-info">
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col-xs-4"><span class="material-icons box-icon">bar_chart</span></div>
                                <div class="col-xs-8">
                                    <p class="card-text inner-text">TOTAL Files</p>
                                    <p class="count-text">{{ $transactions->count() }} <p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card bg-secondary ">
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col-xs-4"><span class="material-icons box-icon">topic</span></div>
                                <div class="col-xs-8">
                                    <p class="card-text inner-text">Storage Used </p>
                                    <p class="count-text">  
                                    @if( $transactions->sum('file_size') / 1000000 <= 0.9 )
                                        {{ round(($transactions->sum('file_size') / 1000),1).'KB' }} 
                                    @else
                                        {{ round(($transactions->sum('file_size') / 1000000),1).'MB'  }} 
                                    @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card bg-success ">
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col-xs-4"><span class="material-icons box-icon">people_alt</span></div>
                                <div class="col-xs-8">
                                    <p class="card-text inner-text">Users </p>
                                    <p class="count-text"> {{ $user->count() }} </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card bg-warning">
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col-xs-4"><span class="material-icons box-icon">storage</span></div>
                                <div class="col-xs-8">
                                    <p class="card-text inner-text">Data Source </p>
                                    <p class="count-text"> {{ $datasources->count() }} </p>
                                </div>            
                            </div>        
                        </div>
                    </div>
                </div>
                </div>
                    
                <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold">Recent Uploads</div>
                        </div>
                    </div>   
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="container">
                        <ul class="nav nav-pills" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="pill" href="#block"><span class="material-icons">grid_view</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="pill" href="#tbl"><span class="material-icons">grid_on</span></a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content">
                        <div id="block" class="container tab-pane active"><br>
                            <div class="container">
                            <div class="card-deck" style="margin-top: 15px;">

                                @foreach ($fileUploads as $transaction)
                                <div class="card">
                                    <div class="card-body shadow-xl sm:rounded-lg">
                                        <div class="row">
                                            <div align="center">
                                                <span class="material-icons" style="font-size:100px; color:grey;">insert_drive_file</span>
                                                <br>
                                                <div class="dropdown dropup">
                                                    <a href="" data-toggle="dropdown" >
                                                        <span class="material-icons">more_horiz</span>
                                                    </a>

                                                    <div class="dropdown-menu">
                                                        <div class="row">
                                                        <div class="col-md-12" >
                                                            <div align="center" >
                                                                <button class="btn btn-xs btn sdropdown-item cp-btn" data-id="{{$transaction->id}}" style="font-size:10px" >
                                                                <span class="material-icons">insert_link</span> <br> Copy Link</button>
                                                            </div>
                                                        </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div align="center" >
                                                                    <a href="{{ route('file-uploads.download', $transaction->id) }}" class="dropdown-item" style="font-size:10px" >
                                                                    <span class="material-icons">file_download</span> <br> Download </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                
                                                {{ $transaction->file_name }}
                                                
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            </div>
                        </div>

                        <div id="tbl" class="container tab-pane fade"><br>
                            <div class="table-responsive">
                            <table class="table table-hover table-bordered ">
                                <thead class="bg-primary" style="color:#ffffff;">
                                    <tr>
                                        <th scope="col">File Name</th>
                                        <th scope="col">File Type</th>
                                        <th scope="col">File Size</th>
                                        <th scope="col">Uploaded By</th>
                                        <th scope="col">Date Uploaded</th>
                                        <th scope="col">Action</th> 
                                    </tr>
                                </thead>
                                <tbody id="up-tb">
                                    @foreach($fileUploads as $fileUpload)
                                    <tr data-id="{{$fileUpload->id}}">
                                        <td>{{ $fileUpload->file_name }}</td>
                                        <td>{{ $fileUpload->file_type }}</td>
                                        <td>
                                        @if( $fileUpload->file_size / 1000000 <= 0.9 )
                                            {{ round(($fileUpload->file_size / 1000),1).'KB' }} 
                                        @else
                                            {{ round(($fileUpload->file_size / 1000000),1).'MB'  }} 
                                        @endif
                                        </td>
                                        <td>{{ $fileUpload->user->name }}</td>
                                        <td>{{ $fileUpload->created_at->format('j F, Y') }}</td>
                                        <td align="center">
                                            <div class="dropdown dropleft">
                                                <a href="" data-toggle="dropdown" ><span class="material-icons">more_vert</span></a>

                                                <div class="dropdown-menu">
                                                    <div class="row">
                                                    @if (auth()->user()->user_type == 1)
                                                        <div class="col-xs-6" >
                                                            <form method="POST" action="{{ route('file-uploads.destroy', $fileUpload->id) }}" class="dropdown-item">
                                                                @csrf
                                                                @method('delete')
                                                                <div align="center">
                                                                    <button onclick="return confirm('Are you very sure?')" class="btn btn-xs action-btn" style="font-size:10px">
                                                                    <span class="material-icons">delete_sweep</span> <br> Delete</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    @endif
                                                        <div class="col-xs-6" >
                                                            <input type="text" style="display:none" id="cp-field{{$fileUpload->id}}" 
                                                                        value="{{ route('file-uploads.download', $fileUpload->id) }}">
                                                            <div align="center" >
                                                                <button class="btn btn-xs sdropdown-item cp-btn action-btn" data-id="{{$fileUpload->id}}" style="font-size:10px" >
                                                                <span class="material-icons">insert_link</span> <br> Copy Link</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">

                                                            <div align="center" >
                                                                <a href="{{ route('file-uploads.download', $fileUpload->id) }}" class="dropdown-item" style="font-size:10px" >
                                                                <span class="material-icons">file_download</span> <br> Download </a>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $fileUploads->links() }}
                        </div>
                        </div>
                    </div>
                     
                </div>

            </div>
        </div>
    </div>

    <script>
    $(function(){
        $('.cp-btn').click(function(){
            /* Get the text field */
            var dataId = $(this).data('id');
            var copyText = $('#cp-field'+dataId).val();   
            var $temp = $("<input>");
            $("body").append($temp);

            // console.log(copyText);  
            $temp.val(copyText).select();
            document.execCommand("copy");
            $temp.remove();

            alert("Download link copied!");
        });

    });
    </script>
</x-app-layout>
