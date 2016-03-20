<h2 class="render-title"><?php _e('Home photo gallery', 'bs_home_gallery'); ?></h2>
<div class="bs_home_gallery_add_box">
    <div class="left">
        <form action="<?php echo osc_admin_render_plugin_url('bs_home_gallery/admin.php'); ?>" method="post"
              enctype="multipart/form-data">
            <input type="hidden" name="newadd" value="true"/>
            <fieldset>
                <div class="form-horizontal">
                    <div class="form-row">
                        <div class="form-label"><?php _e('Title', 'bs_home_gallery') ?></div>
                        <div class="form-controls">
                            <input type="text" name="s_title" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label"><?php _e('Description', 'bs_home_gallery') ?></div>
                        <div class="form-controls">
                            <div class="photo_container">
                                <textarea name="s_description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label"><?php _e('Image', 'bs_home_gallery') ?></div>
                        <div class="form-controls">
                            <div class="photo_container">
                                <input type="file" name="imageName"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label"></div>
                        <div class="form-controls">
                            <input type="submit" value="Save changes" class="btn btn-submit">
                        </div>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
    <div class="right">
        <h2>Info home gallery</h2>

        <p>Home gallery plugin allows you to add images for photo gallery.</p>
        <h4>How does plugin work?</h4>
        <p>Add this code into your main.php or in another file.</p>
        <pre>
            &lt;?php
            foreach(get_all_photo_gallery('asc') as $data){ ?>
                echo get_photo_gallery_image_url($data['s_image']);
                echo $data['s_title'];
                echo $data['s_description'];
            } ?&gt;

        </pre>
        <p>It's compatible with Osclass version 3.3.1 or higher</p>
        <?php printf(__('You have %s version', 'bs_home_gallery'), OSCLASS_VERSION); ?>
    </div>
</div>

<div class="bs_home_gallery_box">
    <table class="table">
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Title</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
        <?php
        if (count(get_all_photo_gallery()) > 0) {
            foreach (get_all_photo_gallery() as $gallery) {
                ?>
                <tr>
                    <td>
                        <?php echo $gallery['bs_key_id']; ?>
                    </td>
                    <td><img src="<?php echo UPLOAD_PATH . $gallery['s_image']; ?>"/></td>
                    <td><?php echo $gallery['s_title']; ?></td>
                    <td><?php echo $gallery['s_description']; ?></td>
                    <td><a class="delete"
                           onclick="javascript:return confirm('<?php _e('This action can not be undone. Are you sure you want to continue?', 'bs_home_gallery'); ?>')"
                           href="<?php echo osc_admin_render_plugin_url('bs_home_gallery/admin.php') . '&delete=' . $gallery['bs_key_id']; ?>"><?php _e('Delete', 'bs_home_gallery'); ?></a>
                    </td>
                </tr>
            <?php }
        } else {
            ?>
            <tr>
                <td colspan="4">
                    <i>No images added</i>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>