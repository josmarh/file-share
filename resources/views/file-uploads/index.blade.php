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
                <button type="button" class="creator btn btn-lg " data-toggle="modal" data-target="#create" title="Upload New File">
                    + </button> 
                <button type="button" class="btn btn-lg btn-danger deletor" id="del-btn" style="display:none"> - </button>
                <br><br>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered ">
                        <thead class="bg-primary" style="color:#ffffff;">
                            <tr>
                            @if (auth()->user()->hasRole('superadministrator'))
                                <th><input type="checkbox"  id="checkall"></th>
                            @endif
                                <th scope="col">@sortablelink('file_name','File Name')</th>
                                <th scope="col">@sortablelink('file_type','File Type')</th>
                                <th scope="col">@sortablelink('file_size','File Size')</th>
                                <th scope="col">Uploaded By</th>
                                <th scope="col">@sortablelink('created_at','Date Uploaded')</th>
                                <th scope="col">Action</th> 
                            </tr>
                        </thead>
                        <tbody id="up-tb">
                            @foreach($fileUploads as $fileUpload)
                            <tr data-id="{{$fileUpload->id}}" id="{{$fileUpload->id}}">
                            @if (auth()->user()->hasRole('superadministrator'))
                                <td>
                                    <input type="checkbox" class="bulk-check" value="{{$fileUpload->id}}">
                                </td>
                            @endif
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
                                            <div class="col-md-12" >
                                                <input type="text" style="display:none" id="cp-field{{$fileUpload->id}}" 
                                                            value="{{ route('file-uploads.download', $fileUpload->id) }}">
                                                <div align="center" >
                                                    <button class="btn btn-xs btn sdropdown-item cp-btn action-btn" data-id="{{$fileUpload->id}}" style="font-size:10px" >
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
                                    <label for="filename">File Name</label>
                                    <input type="text"  class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-300 
                                            focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" name="filename" required />
                                </div>
                                <div class="form-group" style="display:none" id="user">
                                    <label for="email">User to Notify</label>
                                    <input type="email" id="direct-email" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-300 
                                            focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" name="direct-email" />
                                    <div id="user-list"></div>
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

    // bulk delete
    $('#checkall').click(function(){

        if ($(this).prop('checked') == true){
            $('.bulk-check').prop('checked',true);
            $('#del-btn').show();
        }else{
            $('.bulk-check').prop('checked',false);
            $('#del-btn').hide();
        }
        });

        $('#up-tb :checkbox').change(function(){

        if($('#up-tb :checkbox:not(:checked)').length == 0){ 
            // all are checked
            $('#checkall').prop('checked', true);
            $('#del-btn').show();
        } else if($('#up-tb :checkbox:checked').length >  0){
            // all are unchecked
            $('#checkall').prop('checked', false);
            $('#del-btn').show();
        }else{
            $('#del-btn').hide();
        }
        });

        $('#del-btn').click(function(){
        if(confirm("Are you sure you want to delete this?")){
            var delId = [];

            $('.bulk-check:checked').each(function(i){
                delId.push($(this).val());
                element = this;
            });

            if(delId.length>0){
                $.ajax({
                    url: '/file-uploads/bulkdelete',
                    method: 'get',
                    data: {id:delId},
                    success:function(){
                        for(var i=0; i<delId.length; i++)
                        {
                            $('tr#'+delId[i]+'').css('background-color', '#ccc');
                            $('tr#'+delId[i]+'').fadeOut('slow');
                            $('#checkall').prop('checked', false);
                            $('#del-btn').hide();
                        }
                    }
                });
            }
        }
        });

    $('#direct-email').keyup(function(){
        var getUser = $(this).val();

        if (getUser != ''){
            $.ajax({
                url: "{{ url('/file-uploads/directemail') }}",
                method: 'get',
                data: {
                    getUser: getUser,
                },
                success: function(result){
                    $('#user-list').html(result);
                }
            });
        }
    });

    $("#btn").submit(function(){

        if( $('#file-up-field').val() && $.isNumeric( $('#ds-up-field').val() ) )
        {
            $(this).attr('disabled','disabled');
            $(this).html('<span class="spinner-grow spinner-grow-sm"></span> Uploading...')

            return true;
        }else{
            return false;
        }
    });

    $('#ds-up-field').change(function(){

        if($(this).val() == 16 ){
            $('#user').show();
        }else{
            $('#user').hide();
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