
@if($userList->count() > 0)
<ul class="list-group">
    @foreach($userList as $userLists)
        <a href="#" data-id="{{$userLists->email}}" class="list-group-item list-group-item-action">{{ $userLists->email }}</a>
    @endforeach
</ul>
@else
{{ 'User Mail Not Found!' }}
@endif

<script>
$(function(){
    $('.list-group-item').click(function(){
        var email = $(this).data('id');
        $('#direct-email').val(email);
        //console.log(email);
        $(this).hide();
    });
});
</script>