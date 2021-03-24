<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div id='dashboard'>
        <div id="gif">
            <center><img src="{{ asset('images/circle.gif') }}" alt="loader" id="imgs" style="width:200px;height:200px;margin-top:150px;"></center>
        </div>
    </div>

<script>
$(function(){

function dashboard(){

    $.ajax({
        url: '/render',
        method: 'get',
        success: function(result){
            $('#gif').hide();
            $('#dashboard').html(result);
        }
    });
}

dashboard();

});
</script>
</x-app-layout>