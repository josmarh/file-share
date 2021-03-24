$(function(){
    

    //filters
    if ( $('#name').val() || $('#email').val() || $('#role').val() !='All' || $('#status').val() !='All') {
        $('#filter-section').show();
    }else{
        $('#filter-section').hide();
    }  

    $('#filter').click(function(){
        $('#filter-section').toggle();
    });

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

    $('#ms-tb :checkbox').change(function(){

        if($('#ms-tb :checkbox:not(:checked)').length == 0){ 
            // all are checked
            $('#checkall').prop('checked', true);
            $('#del-btn').show();
        } else if($('#ms-tb :checkbox:checked').length >  0){
            // all are unchecked
            $('#checkall').prop('checked', false);
            $('#del-btn').show();
        }else{
            $('#del-btn').hide();
        }
    });

    $('#del-btn').click(function(){
        if(confirm("Please ensure files uploaded by the user(s) have been deleted before you proceed?")){
            var delId = [];

            $('.bulk-check:checked').each(function(i){
                delId.push($(this).val());
                element = this;
            });

            if(delId.length>0){
                $.ajax({
                    url: '/user/bulkdelete',
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
                        location.reload();
                    }
                });
            }
        }
    });


    // button changes
    var arr=[];

    $('#ms-tb tr').each( function (i, tr) {
        arr.push($(tr).data('id'));
    });
    // console.log(arr);
    for (var i=0; i<arr.length; i++){

        // switch button based on user role
        if ( $('#status'+arr[i]).text() == 'Admin' )
        {
            $('#ms-btn'+arr[i]).html('<span class="material-icons" >person</span> <br> Make Basic User');
            // $('#ms-btn'+arr[i]).removeClass( "btn-outline-success" ).addClass( "btn-outline-warning" );
            $('#main-status'+arr[i]).val('user');
            $('#main2-status'+arr[i]).val('superadministrator');
        }else{
            $('#main-status'+arr[i]).val('superadministrator');
            $('#main2-status'+arr[i]).val('user');
        }

        // switch button based on active status
        if ( $('#active-status'+arr[i]).text() == 'Active' )
        {
            $('#ss-btn'+arr[i]).html('<span class="material-icons" id="active_status{{ $user->id }}" >clear</span> <br> Inactive');
            $('#active_status'+arr[i]).val('2');
        }else{
            $('#active_status'+arr[i]).val('1');
        }
    }


});