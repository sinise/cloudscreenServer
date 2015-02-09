<div class="container">
    <h1>Manage your info screens</h1>
    <div class="box">

        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>
        <h3></h3>
        <div>
            <table class="overview-table">
                <thead>
                <tr>
                    <td>Mac</td>
                    <td>ip</td>
                    <td>wan</td>
                    <td>cpu</td>
                    <td>ram</td>
                    <td>url</td>
                    <td>Url via Cloudscreen</td>
                    <td>orientation</td>
                    <td>Transfer time</td>
                    <td>Last message</td>
                    <td>Config</td>
                </tr>
                </thead>
                <?php foreach ($this->macs as $mac) { ?>
                        <td><?= $mac->mac; ?></td>
                        <td><?= $mac->ip; ?></td>
                        <td><?= $mac->wan; ?></td>
                        <td><?= $mac->cpu; ?></td>
                        <td><?= $mac->ram; ?></td>
                        <td><?= $mac->url; ?></td>
                        <td><?= $mac->urlViaServer; ?></td>
                        <td><?= $mac->orientation; ?></td>
                        <td><?= $mac->lastMTransTime; ?></td>
                        <td><?= $mac->createTime; ?></td>
                        <td>
                            <a href="<?= Config::get('URL') . 'rpi/configRpi/' . $mac->mac; ?>">Config</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

