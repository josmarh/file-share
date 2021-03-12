
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div><br />
@endif

<form method="POST" action="{{ route('mail-subscribers.update', $mailSubscribers->id) }}">
    @csrf
    @method('put')
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="ms-name-update" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-300 
                focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" value="{{ $mailSubscribers->name }}" name="name" required />
    </div>

    <div class="form-group">      
        <label for="email">Email</label>
        <input type="email" id="ms-email-update" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-300 
        focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" value="{{ $mailSubscribers->email }}" name="email" required />
    </div>
    <button type="submit" class="btn btn-primary float-right" id="btn-update">Update </button>
</form>

<script>
$(function(){
    $("#btn-update").click(function(){

        if( $('#ms-name-update').val() && $('#ms-email-update').val() )
        {
            $(this).attr('disabled','disabled');
            $(this).html('<span class="spinner-grow spinner-grow-sm"></span> Updating...')

            return true;
        }else{
            return false;
        }
    });
});
</script>