<h2 class="head">Social Conversations</h2>
<span>
    Please sign in to add a comment:
    <a href="<?=Yii::app()->baseUrl."/social/authenticate?media=google"; ?>" onclick="window.open(this.href, '_blank' ,'toolbar=no, scrollbars=yes, resizable=yes, top=0, left=0, width=400, height=400'); return false;" class="authenticate_social" data-attr-id="google" target="_blank">Youtube</a>,
    <a href="<?=Yii::app()->baseUrl."/social/authenticate?media=facebook"; ?>" onclick="window.open(this.href, '_blank' ,'toolbar=no, scrollbars=yes, resizable=yes, top=0, left=0, width=400, height=400'); return false;" class="authenticate_social" data-attr-id="facebook" target="_blank">Facebook</a> or
    <a href="<?=Yii::app()->baseUrl."/social/authenticate?media=twitter"; ?>" onclick="window.open(this.href, '_blank' ,'toolbar=no, scrollbars=yes, resizable=yes, top=0, left=0, width=400, height=400'); return false;" class="authenticate_social" data-attr-id="twitter" target="_blank">Twitter</a>
</span>
<div class="join-conversation">
    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/no-photo.jpg" alt="" class="profile-image">
    <input type="text" placeholder="Join the conversation" id="post_text" value="">
    <input type="submit" value="Post" name="submit" id="submit_post">
    <small id="messageBox" style="display:none">Posted Successfully!</small>
    <input type ="hidden" value='' id='scm_login' />
    <input type ="hidden" value='' id='scm_identifier' />
</div>
<div id="post-auth-buttons" class ="social-icons" style="">
    <div>Please Sign In</div>
    <a target="_blank" href="<?=Yii::app()->baseUrl."/social/authenticate?media=facebook"; ?>" onclick="window.open(this.href, '_blank' ,'toolbar=no, scrollbars=yes, resizable=yes, top=0, left=0, width=400, height=400'); return false;" data-external-service-name="facebook" data-attr-id="facebook" class="button login-button-facebook social_facebook authenticate_social"></a>
    <a target="_blank" href="<?=Yii::app()->baseUrl."/social/authenticate?media=twitter"; ?>" onclick="window.open(this.href, '_blank' ,'toolbar=no, scrollbars=yes, resizable=yes, top=0, left=0, width=400, height=400'); return false;" data-external-service-name="twitter" data-attr-id="twitter" class="button login-button-twitter social_twitter authenticate_social"></a>
    <a target="_blank" href="<?=Yii::app()->baseUrl."/social/authenticate?media=google"; ?>" onclick="window.open(this.href, '_blank' ,'toolbar=no, scrollbars=yes, resizable=yes, top=0, left=0, width=400, height=400'); return false;" data-external-service-name="youtube" data-attr-id="youtube" class="button login-button-youtube social_youtube authenticate_social"></a>
</div>
<div id="scm-posts" class="posts-wrapper"></div>