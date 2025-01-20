<?php $this->view("admin/header", $data); ?>

<?php $this->view("admin/sidebar", $data); ?>

<style type="text/css">
    .details {
        background-color: #eee;
        box-shadow: 0px 0px 10px #aaa;
        width: 100%;
        position: absolute;
        min-height: 100px;
        left: 0px;
        padding: 10px;
        ;
        z-index: 2;
    }

    .hide {
        display: none;
    }
</style>

<form method="post" enctype="multipart/form-data">
    <table class="table">

        <?php if ($type == "socials"): ?>

            <thead>
                <tr>
                    <th>Setting</th>
                    <th>Value</th>
                </tr>
            </thead>

            <tbody>

                <?php if (isset($settings) && is_array($settings)): ?>
                    <?php foreach ($settings as $setting): ?>

                        <tr>
                            <td><?= ucwords(str_replace("_", " ", $setting->setting)) ?></td>
                            <td> 
                            <?php if ($setting->type == "" || $setting->type == "text"): ?>    
                            <input placeholder="<?= ucwords(str_replace("_", " ", $setting->setting)) ?>"
                                    name="<?= $setting->setting ?>" class="form-control" type="text"
                                    value="<?= $setting->value ?>" /> </td>
                            <?php elseif ($setting->type == "textarea"): ?>  
                                <textarea placeholder="<?= ucwords(str_replace("_", " ", $setting->setting)) ?>" name="<?= $setting->setting ?>" class="form-control" > <?= $setting->value ?> </textarea>  
                            <?php endif; ?>
                        </tr>

                    <?php endforeach; ?>
                <?php endif; ?>

            </tbody>

            <input type="submit" value="Save Settings" class="btn btn-warning pull-right" />

        <?php elseif ($type == "slider_images"): ?>

            <?php if ($action == "show"): ?>
                <thead>
                    <tr>
                        <th>Header 1 Text</th>
                        <th>Header 2 Text</th>
                        <th>Main Message</th>
                        <th>Product Link</th>
                        <th>Product Image</th>
                        <th>Disabled</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (isset($rows) && is_array($rows)): ?>
                        <?php foreach ($rows as $row): ?>

                            <tr>
                                <td> <?= $row->header1_text ?> </td>
                                <td> <?= $row->header2_text ?> </td>
                                <td> <?= $row->text ?> </td>
                                <td> <?= $row->link ?> </td>
                                <td> <img src="<?= ROOT . $row->image ?>" style="width: 100px;" /> </td>
                                <td> <?= $row->disabled ? "Yes" : "No" ?> </td>
                            </tr>

                        <?php endforeach; ?>
                    <?php endif; ?>

                </tbody>

                <a href="<?= ROOT ?>admin/settings/slider_images?action=add">
                    <input type="button" value="Add row" class="btn btn-warning pull-right" />
                </a>

            <?php elseif ($action == "add"): ?>
                <h2>Add new row</h2>

                <div class="form-group">
                <label for="header1_text">Header 1 Text</label>
                    <input id="header1_text" name="header1_text" type="text" value="<?= (isset($POST['header1_text'])) ? $POST['header1_text'] : ''; ?>"  class="form-control"  placeholder="" autofocus />
                </div>

                <div class="form-group">
                <label for="header2_text">Header 2 Text</label>
                    <input id="header2_text" name="header2_text" type="text" value="<?= (isset($POST['header2_text'])) ? $POST['header2_text'] : ''; ?>"  class="form-control"  placeholder="" />
                </div>

                <div class="form-group">
                <label for="text">Main Message</label>
                    <textarea id="text" name="text" class="form-control"> <?= (isset($POST['text'])) ? $POST['text'] : ''; ?> </textarea>
                </div>

                <div class="form-group">
                <label for="link">Product Link</label>
                    <input id="link" name="link" type="text" value="<?= (isset($POST['link'])) ? $POST['link'] : ''; ?>"  class="form-control"  placeholder="e.g http://yourwebsite.com/your_product" />
                </div>

                <div class="form-group">
                <label for="image">Product Image</label>
                    <input id="image" type="file" name="image" class="form-control">
                </div>

                <input type="submit" value="Add" class="btn btn-primary">

            <?php endif; ?>
        <?php endif; ?>

    </table>

</form>

<?php $this->view("admin/footer", $data); ?>