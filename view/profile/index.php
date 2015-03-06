<div class="container">
    <h1>ProfileController/index</h1>
    <div class="box">

        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>

        <h3>What happens here ?</h3>
        <div>
            This controller/action/view shows a list of all users in the system. You could use the underlying code to
            build things that use profile information of one or multiple/all users.
        </div>
        <div>
            <table class="overview-table">
                <thead>
                <tr>
                    <td>Id</td>
                    <td>Link to user's profile</td>
                </tr>
                </thead>
                <?php foreach ($this->users as $user) { ?>
                        <td><?= $user->user_id; ?></td>
                        <td>
                            <a href="<?= Config::get('URL') . 'profile/showProfile/' . $user->user_id; ?>"><?= $user->user_name?></a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
