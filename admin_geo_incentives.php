<?php


// Hook for adding admin menus
add_action('admin_menu', 'mt_add_pages');

// action function for above hook
function mt_add_pages() {
    // Add a new submenu under Tools:
    add_management_page( __('KML Upload','menu-test'), __('KML Upload','menu-test'), 'manage_options', 'kmlupload', 'mt_tools_page');
}

// mt_settings_page() displays the page content for the Test settings submenu
function mt_tools_page() {

    //must check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    $text = '';
    $link = 'http://';
    $id = '';
    $location = '';

    //form for submitting new/edit file
    if(isset($_REQUEST['text_result']) && isset($_REQUEST['text_link']) && isset($_REQUEST['id'])) {

        $file_options = get_option('geo_incentive_files');

        if(isset($_FILES['datafile'])) {
            $moved = move_uploaded_file($_FILES['datafile']['tmp_name'], dirname(__FILE__)."/kml/".$_FILES['datafile']['name']);
            $key_name = $_FILES['datafile']['name'];
        } else {
            $moved = true;
            $key_name = $_REQUEST['datafile'];
        }

        if($moved) {
             $file_options[$key_name] = array(
                'url'=>plugins_url().'/geo_incentives/kml/'.$key_name,
                'text'=>$_REQUEST['text_result'], 
                'link'=>$_REQUEST['text_link'],
                'id'=>$_REQUEST['id'],
                'location'=>dirname(__FILE__)."/kml/".$key_name,
            );
            update_option('geo_incentive_files', $file_options);
            echo '<h3 style="color: green;">File uploaded.</h3>';
        } else {
            echo '<h3 style="color: red;">Error uploading file.</h3>';
        }
    } else if(isset($_REQUEST['no_result'])) {
        update_option('geo_no_result', $_REQUEST['no_result']);
        echo '<h3 style="color: green;">Text was saved.</h3>';
    } else if(isset($_REQUEST['delete_button'])) {
        $file_id = $_REQUEST['delete_button'];
        $file_options = get_option('geo_incentive_files');

        unlink($file_options[$file_id]['location']);
        unset($file_options[$file_id]);

        update_option('geo_incentive_files', $file_options);

        echo '<h3 style="color: green;">Item was removed.</h3>';
    } else if(isset($_REQUEST['edit_button'])) {
        $file_options = get_option('geo_incentive_files');
        $file_id = $_REQUEST['edit_button'];
        
        $id = $file_options[$file_id]['id'];
        $location = $file_options[$file_id]['location'];
        $text = $file_options[$file_id]['text'];
        $url = $file_options[$file_id]['url'];
        $link = $file_options[$file_id]['link'];
    }

    $file_options = get_option('geo_incentive_files');
    $no_result = get_option('geo_no_result');
?>
    <!-- File upload form -->
    <h3>Upload new KML file.</h3>
    <form name="file_submit" method="POST" action="" enctype="multipart/form-data">
        <p>
            Description:
            <input type="text" name="id" value="<?php echo $id; ?>"/>
        </p>
        <p>
            Result Text: 
            <input type="text" name="text_result" value="<?php echo $text; ?>"/>
        </p>

        <p>
            Result Link:
            <input type="text" name="text_link" value="<?php echo $link; ?>"/>
        </p>   

        <?php if(!isset($_REQUEST['edit_button'])) { ?>
            <p>
                Upload KML file: 
                <input type="file" name="datafile">
            </p>
        <?php } else { ?>
                KML file:
                <input type="text" name="datafile" value="<?php echo $_REQUEST['edit_button']; ?>" readonly/>
                <br>
        <?php } ?>
        <input type="submit" name="submit" value="Save">

    </form>

    <hr>

    <form name="no_result_form" method="POST" action="" enctype="multipart/form-data">
        <p>
            No results text: 
            <input type="text" name="no_result" />
        </p>
        <p>
            Current text: <?php echo $no_result; ?>
        </p>

        <input type="submit" name="submit" value="Save">

    </form>

    <hr>

    <!-- Insert table here -->
    <form name="remove_item_form" method="POST" action="" enctype="multipart/form-data">
        <table cellspacing="0" cellpadding="10" border>
            <tr>
                <th>Description</th>
                <th>Text</th>
                <th>Link</th>
            </tr>

            <?php foreach ($file_options as $key => $file_info) { ?>

                    <tr id="<?php echo 'tr_'.$key; ?>">
                        <td><?php echo $file_info['id']; ?></td>
                        <td><?php echo $file_info['text'];?></td>
                        <td><?php echo $file_info['link'];?></td>
                        <td><button class="edit_button" name="edit_button" value="<?php echo $key;?>">Edit</button></td>
                        <td><button class="delete_button" name="delete_button" value="<?php echo $key;?>">Delete</button></td>
                    </tr>
            <?php } ?>
        </table>
    </form>

<?php
}