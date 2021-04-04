$(function(){
   
    // filter
    if ( $('#file_name').val() || $('#created_by').val() != '' 
        || $('#created_from').val() !='' || $('#created_to').val() !='' ) {

        $('#filter-section').show();
    }else{
        $('#filter-section').hide();
    }

    $('#filter').click(function(){
        $('#filter-section').toggle();
    });

    $('[data-toggle="popover"]').popover();

    $('.date').datepicker({  
        format: 'yyyy-mm-dd',
    }); 
    $('.datepicker-dropdown').hide();
    $('.date').click(function(){
        $('.datepicker-dropdown').hide();
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

    $('#up-tb :checkbox').change(function(){

        if($('#up-tb :checkbox:not(:checked)').length == 0){ 
            // all are checked
            $('#checkall').prop('checked', true);
            $('#del-btn').show();
        }else if($('#up-tb :checkbox:checked').length > 0){
            // all are unchecked
            $('#checkall').prop('checked', false);
            $('#del-btn').show();
        }else if($('#up-tb :checkbox:checked').length == 0){
            $('#checkall').prop('checked', false);
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
                        location.reload();
                    }
                });
            }
        }
    });

    // get user email if existing
    $('#direct-email').keyup(function(){
        var getUser = $(this).val();

        if (getUser != ''){
            $.ajax({
                url: '/file-uploads/directemail',
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

    // on form submit 
    $("#btn").submit(function(){

        if( $('#file-up-field').val() && $.isNumeric( $('#ds-up-field').val() ) )
        {
            if ($("direct-email").val()){
                $("direct-email").multiple = true;
            }
            $(this).attr('disabled','disabled');
            $(this).html('<span class="spinner-grow spinner-grow-sm"></span> Uploading...')

            return true;
        }else{
            return false;
        }
    });

    // if datasource is not available i.e other show user filed
    $('#ds-up-field').change(function(){

        if($(this).val() == 2 ){
            $('#user').show();
        }else{
            $('#user').hide();
        }
    });

    // copy to clipboard
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