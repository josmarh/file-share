$(function(){
    // filter
    if ( $('#name').val() || $('#email').val() || $('#status').val() !='Choose Status') {
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
        if(confirm("Are you sure you want to delete this?")){
            var delId = [];

            $('.bulk-check:checked').each(function(i){
                delId.push($(this).val());
                element = this;
            });

            if(delId.length>0){
                $.ajax({
                    url: '/mail-subscribers/bulkdelete',
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

     // button changes
    var arr=[];

    $('#ms-tb tr').each( function (i, tr) {
        arr.push($(tr).data('id'));
    });
    // console.log(arr);
    for (var i=0; i<arr.length; i++){

        if ( $('#status'+arr[i]).text() == 'Subscribed' )
        {
            $('#ms-btn'+arr[i]).html('<span class="material-icons" >clear</span> <br> Unsubscribe');
            // $('#ms-btn'+arr[i]).removeClass( "btn-outline-success" ).addClass( "btn-outline-warning" );
            $('#main-status'+arr[i]).val('2');
            

        }else{
            $('#main-status'+arr[i]).val('1');
        }
    }

    // on form submit
    $("#btn").submit(function(){

        if( $('#ms-name').val() && $('#ms-email').val() )
        {
            $(this).attr('disabled','disabled');
            $(this).html('<span class="spinner-grow spinner-grow-sm"></span> Saving...')

            return true;
        }else{
            return false;
        }
    });

});