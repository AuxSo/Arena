
<section>
    <article>
        <h3 id="indexTitle">Welcome in WebArena</h3>

            <p class="index">Web Arena is a RPG. You can create your own fighters, play online with other players on the arena.</p>
            <p class="index">  You can take tools which will help you to improve your level and be the best fighter.</p>
            <?php if(!$isConnected){ ?><p id="index">    Create an account and play now!</p> <?php }?>
    </article>
</section>

<?php if($isConnected){ ?>
<section class="inscriptionSection">
    <section id="zone" class="hidden">
        <form method="post" >
            <label>Enter your old password</label>
            <input type="password" name="oldPassword">
            <label>Enter your new password</label>
            <input type="password" name="newPassword1">
            <label>Confirm your new password</label>
            <input type="password" name="newPassword2">
            <input class="loginButton" type="submit" id="changePassword" name="changePassword" value="Change my password">
        </form>
    </section>
    <div id="showBloc" style="margin-top:50px">I forgot my password</div>
</section>
<?php } ?>

<?php $this->assign('title','Home');?>
