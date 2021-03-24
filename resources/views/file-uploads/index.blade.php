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
                <button class="btn btn-outline-primary filter-btn" style="margin-left: 20px;" id="filter">
                    Filters <span class="fa fa-filter"></span>
                </button>
                <br><br>
                <!-- filters -->
                <form method="GET" action="{{ route('file-uploads') }}" id="filter-section">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="file_name">File Name</label>
                                <input type="text" value="{{ request()->query('file_name') }}" id="file_name" name="file_name" 
                                        class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-300 
                                                focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                                >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="created_by">Created By</label>
                                <input type="text" value="{{ request()->query('created_by') }}" id="created_by" name="created_by" 
                                        class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-300 
                                               focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" >
                                <label for="created_from">Created Date</label>
                                <input type="date" value="{{ request()->query('created_from') }}" id="created_from" name="created_from" 
                                        class="date form-control block mt-1 w-full border-gray-300 focus:border-indigo-300 
                                            focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="created_to">To</label>
                            <div class="form-group" >
                                <input type="date" value="{{ request()->query('created_to') }}" id="created_to" name="created_to" 
                                        class="date  block mt-1 w-full border-gray-300 focus:border-indigo-300 
                                            focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm ">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn bg-primary" style="color:#ffffff;">Search</button> 
                </form><br>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered ">
                        <thead class="bg-primary" style="color:#ffffff;">
                            <tr>
                            @if (auth()->user()->hasRole('superadministrator'))
                                <th width="10"><input type="checkbox"  id="checkall"></th>
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
                            @forelse($fileUploads as $fileUpload)
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
                            @empty
                            {{ __('Data Not Available!') }}
                            @endforelse
                        </tbody>
                    </table>
                    {{ $fileUploads
                        ->appends(['file_name'=>request()->query('file_name'),
                                    'created_by'=>request()->query('created_by'),
                                    'created_from'=>request()->query('created_from'),
                                    'created_to'=>request()->query('created_to'),])
                        ->links() }}
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
                                    <label for="datasource" class="labels">Upload Type</label>
                                    <select class="custom-select block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 
                                        focus:ring-opacity-50 rounded-md shadow-sm" name="datasource" id="ds-up-field" required >
                                        <option selected>Choose Upload Type</option>
                                        <option value="1">{{ __('General') }}</option>
                                        <option value="2">{{ __('Direct') }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="filename">File Name</label>
                                    <input type="text"  class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-300 
                                            focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" name="filename" required />
                                </div>
                                <div class="form-group" style="display:none" id="user">
                                    <label for="email" >User(s) Email</label>
                                    <input type="email" id="direct-email" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-300 
                                            focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" data-html="true" 
                                            data-toggle="popover" data-trigger="focus" title="<i class='fa fa-info-circle'></i> Tips" data-placement="top" 
                                            data-content="To add multiple emails, separate each email with a comma, like: name@example.com, name2@example.com." 
                                            name="direct-email" multiple />
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

<script src="{{ asset('js/fileUpload.js') }}"></script>
</x-app-layout>