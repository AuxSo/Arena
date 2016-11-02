
<section>
    <article>
        <h1>Welcome in WebArena</h1>

            <p>Web Arena is a RPG. You can create your own fighters, play online with other players on the arena.
                You can take tools which will help you to improve your level and be the best fighter.
                Create an account and play now!
            </p>
    </article>
</section>

<?php if($isConnected){ ?>
    <section>
        <form method="post" id="zone" class="hidden">
            <label>Enter your old password</label>
            <input type="password" name="oldPassword" id="oldPassword">
            <label>Enter your new password</label>
            <input type="password" name="newPassword1" id="password">
            <label>Confirm your new password</label>
            <input type="password" name="newPassword2" id="password">
            <input type="submit" id="changePassword" class=loginButton" name="changePassword" value="Change my password">
        </form>

        <div id="showBloc" style="margin-top:50px">I forgot my password</div>
    </section>
<?php } ?>

<?php $this->assign('title','Home');?>
