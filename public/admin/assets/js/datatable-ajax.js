$(document).ready( function(){
    var ids;
    var action;
    var table = $('#_ajax_datatable').DataTable({
        "processing": true,
        "serverSide": true,
        "ordering": false,
        "searching": false,
        "bLengthChange": false,
        "ajax":{
            url : window.location.href + '/ajax-listing',
            type: "GET",
            beforeSend : function(){

            },
            data : function(d) {
                d.keyword = $('input[name="keyword"]').val();
            },
            error: function(){  // error handling

            }
        },
        drawCallback: function (settings) {
            // other functionality
        }
    });

    $(document).on( 'click','._delete_record',function(e){
        e.preventDefault();
        var slug = $(this).parent().parent().find('.record_id').val();
        alertify.confirm('Confirmation Alert', 'Are you sure you want to delete this record?', function(){
           //confirm
           let request_url = window.location.href + '/delete-record';
           ajaxRequest(request_url,'DELETE',{slug:slug}).then( function(res){
                $.toast({
                    heading: 'Success',
                    text: res.message,
                    icon: 'success',
                    position:'top-right',
                })
               table.ajax.reload();
           }).catch(err => alert(err.message))

        } , function(){
            //cancel
        });
    })

    $('#search_form').submit( function(e){
        e.preventDefault();
        if( $('input[name="keyword"]').val() != ''){
            table.ajax.reload();
        }
    })

    $(document).on('click','.checked_all',function(){
        if( $(this).is(':checked') ){
            $('.record_id').prop('checked',true);
        } else {
            $('.record_id').prop('checked',false);
        }
    })

    $('.bulk_delete').click(function(e){
        e.preventDefault();
        var ids = []
        $('.record_id:checked').each( function(){
            ids.push( $(this).val() )
        });
        if( ids.length > 0 ){
            alertify.confirm('Confirmation Alert', 'Are you sure you want to delete records?', function(){
                //confirm
                let request_url = window.location.href + '/delete-record';
                ajaxRequest(request_url,'DELETE',{id:ids}).then( function(res){
                    $.toast({
                        heading: 'Success',
                        text: res.message,
                        icon: 'success',
                        position:'top-right',
                    })
                })
                table.ajax.reload();
            } , function(){
                //cancel
            });
        } else {
            alertify.alert('Alert ','Kindly select a record', () => {});
        }
    })
})

