<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('File Upload') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="card-body">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div><br />
                @endif
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('status') }}
                    </div>
                @endif
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create"> <b>Upload File</b> </button> <br><br>
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
                                            <div class="col-xs-6" >
                                                <form method="POST" action="{{ route('file-uploads.destroy', $fileUpload->id) }}" class="dropdown-item">
                                                    @csrf
                                                    @method('delete')
                                                    <div align="center">
                                                        <button onclick="return confirm('Are you very sure?')" class="btn btn btn-xs " style="font-size:10px">
                                                        <span class="material-icons">delete_sweep</span> <br> Delete</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-xs-6" >
                                                <input type="text" style="display:none" id="cp-field{{$fileUpload->id}}" 
                                                            value="{{ route('file-uploads.download', $fileUpload->id) }}">
                                                <div align="center" >
                                                    <button class="btn btn-xs btn sdropdown-item cp-btn" data-id="{{$fileUpload->id}}" style="font-size:10px" >
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
             <!-- The Modal -->
             <div class="modal fade" id="create">
                <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header bg-primary" style="color:#ffffff;">
                            <h4 class="modal-title "><b>New File Upload</b></h4>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">
                           
                            <form method="POST" action="{{ route('file-uploads.store') }}" id="upload-form" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="datasource" class="labels">Data Source</label>
                                    <select class="custom-select block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 
                                        focus:ring-opacity-50 rounded-md shadow-sm" name="datasource" id="ds-up-field" required >
                                        <option selected>Choose Data Source</option>
                                        @foreach ($dataSource as $dataSources)
                                        <option value="{{ $dataSources->id }}">{{ $dataSources->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="file">Choose a File</label>
                                    <input type="file" id="file-up-field" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-300 
                                            focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" name="file" 
                                            accept=".csv, text/csv, .pdf, .zip, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required />
                                    <span style="color:red; font-size:15px">accept only .csv, .xls, .xlsx, .pdf, .zip upto 500mb</span>
                                </div>
                                <button type="submit" class="btn btn-primary float-right" id="btn">Save </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
$(function(){

    $("#btn").click(function(){

        if( $('#file-up-field').val() && $.isNumeric( $('#ds-up-field').val() ) )
        {
            $(this).attr('disabled','disabled');
            $(this).html('<span class="spinner-grow spinner-grow-sm"></span> Uploading...')

            return true;
        }else{
            return false;
        }
    });

        
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