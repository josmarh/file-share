$(function(){

    if ( $('#search').val() ) {
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

    $('#ds-tb :checkbox').change(function(){

        if($('#ds-tb :checkbox:not(:checked)').length == 0){ 
            // all are checked
            $('#checkall').prop('checked', true);
            $('#del-btn').show();
        } else if($('#ds-tb :checkbox:checked').length >  0){
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
                    url: '/data-sources/bulkdelete',
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

    $("#btn").submit(function(){

        if( $('#ds-field').val() )
        {
            $(this).attr('disabled','disabled');
            $(this).html('<span class="spinner-grow spinner-grow-sm"></span> Saving...');
        }
    });

    

});