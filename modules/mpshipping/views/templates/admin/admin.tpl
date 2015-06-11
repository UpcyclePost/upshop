<link rel="stylesheet" type="text/css" href="{$this_path|escape:'htmlall'}css/admin.css">
<div class="mp_shipp_conf">

    <div class="bootstrap" style="display:none;">
        <div class="module_error alert alert-danger" style="display:none;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <span></span>
        </div>
        <div class="module_confirmation conf confirm alert alert-success" style="display:none;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
             <span></span>
        </div>
    </div>


    <form action="{$smarty.server.REQUEST_URI|escape:'htmlall'}" method="post">
        <div class="panel">
            <div class="panel-heading"><i class="icon-cogs"></i> {l s='Carrier Setting' mod='mpshipping'}</div>
            <div class="form-wrapper">
                <div class="form-group" style="height: 5px;">
                    <label class="control-label col-lg-3">
                        <span title="" data-html="true" data-toggle="tooltip" class="label-tooltip" data-original-title="Assign carrier to products which are not from marketplace.">
                            {l s='Assign carriers to main products' mod='mpshipping'}
                        </span>
                    </label>
                    <div class="col-lg-9 ">
                        <input type="submit" name="mp_shipp_add" id="mp_shipp_add" value="{l s='Assign' mod='mpshipping'}" class="button" />
                        <img src="{$this_path|addslashes}img/loader.gif" class="loader" style="display:none;" alt="{l s='Loading' mod='mpshipping'}" title="{l s='Loading' mod='mpshipping'}" />
                    </div>
                </div>
            </div><!-- /.form-wrapper -->
        </div>
    </form>
</div>

<script type="text/javascript">
$(document).ready(function(){
    $('#mp_shipp_add').on('click', function(e){
        e.preventDefault();
        var loader = $('.loader');
        loader.show();
        $.ajax({
            url: '{$admin_mpshipping_url}',
            data: {
                action: "updateCarrierToMainProducts",
                ajax: "1"
            },
            dataType: "json",
            async: false,
            success: function(result)
            {
                if (result.status == 'ok')
                {
                    $('.bootstrap').show();
                    $('.module_confirmation').show();
                    $('.module_confirmation span').text(result.msg);
                    loader.hide();
                }
                else
                {
                    $('.bootstrap').show();
                    $('.module_error').show();
                    $('.module_error span').text(result.msg);
                    loader.hide();
                }
            }
        });
        return false;
    });
});
</script>