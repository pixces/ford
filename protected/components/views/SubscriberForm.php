<?php if ($this->env != 'youtube' ) { ?>
<div id="before-subscribe">
    <h2 class="head">Subscribe to Alerts</h2>
    <div class="form">
        <form name="subscribe" id="subscribe">
            <input type="hidden" id="pageTitle" name="page_title" value="<?=$this->getPageName(); ?>">
            <input type="hidden" id="userIp" name="user_ip" value="<?=$this->getUserIp(); ?>">
            <div class="row">
                <input name="name" placeholder="Name" type="text" id='sub-name' value="">
            </div>
            <div class="row last">
                <input name="email" placeholder="Email" type="email" id='sub-email' value="">
                <input type="submit" name="alerts-submit" class="alerts-submit" value="" id='subscribe-btn'>
            </div>
        </form>
    </div>
</div>
<div id="thank-you">
    <h2>Thank You</h2>
    <small class="subscribe-message">for subscribing to our newsletter.</small>
</div>
<script>
    $(function(){
        $("#subscribe").validate({
            debug: true,
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "Please enter your name"
                },
                email: {
                    required: "Please enter your email",
                    rules: 'valid_email'
                }
            },
            errorPlacement: function (error, element) {
                element.after( error );
            },
            submitHandler:function(form){
                SUBSCRIBE.emailSubscribe();
            }
        });
    });
</script>
<?php } ?>