<div class="container">

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <!-- login box on left side -->
    <div class="login-box">
        <h2>Configure infoscreen <?= $this->mac->mac; ?></h2>
  <div>
            <table class="configRpi">
                <thead>
                <tr>
                    <td>Screen orientation</td>
                    <td>Url</td>
                    <?php if (Session::get('user_account_type') == 3) { ?>
                        <td>urlViaServer</td>
                        <td>command</td>
                    <?php } ?>
                </tr>
                    <form method="post" action="<?php echo Config::get('URL'); ?>rpi/configRpi_action">
                <tr>
                    <td><input type="radio" name="orientation" value="0" checked>Landscape<br>
                    <input type="radio" name="orientation" value="1">Potrait</td>
                    <td><input type="url" name="url" value="<?= $this->mac->url ?>"/></td>
                    <?php if (Session::get('user_account_type') == 3) { ?>
                        <td><input type="text" placeholder="0" name="urlViaServer"/></td>
                        <td><input type="text" placeholder="shell command to execute" name="command"/></td>
                    <?php } ?>
                        <td><input type="hidden" name="mac" value="<?= $this->mac->mac ?>" /></td>
            </table>

            <input type="submit" value="Submit" />

        </div>
        </form>
    </div>
</div>
