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

    if(isset($_REQUEST['text_result']) && isset($_REQUEST['text_link']) && isset($_FILES['datafile'])) {

        $file_options = get_option('geo_incentive_files');
        $file_options[ $_FILES['datafile']['name'] ] = array(
            'url'=>plugins_url().'/geoincentives/kml/'.$_FILES['datafile']['name'],
            'text'=>$_REQUEST['text_result'], 
            'link'=>$_REQUEST['text_link']
        );

        $moved = move_uploaded_file($_FILES['datafile']['tmp_name'], dirname(__FILE__)."/kml/".$_FILES['datafile']['name']);

        if($moved) {
            update_option('geo_incentive_files', $file_options);
            echo '<h3 style="color: green;">File uploaded.</h3>';
        } else {
            echo '<h3 style="color: red;">Error uploading file.</h3>';
        }
    } else if(isset($_REQUEST['no_result'])) {
        update_option('geo_no_result', $_REQUEST['no_result']);
        echo '<h3 style="color: green;">Text was saved.</h3>';
    }
?>
    <!-- File upload form -->
    <h3>Upload new KML file.</h3>
    <form name="file_submit" method="POST" action="" enctype="multipart/form-data">
        <p>
            Result Text: 
            <input type="text" name="text_result" />
        </p>

        <p>
            Result Link:
            <input type="text" name="text_link" value="http://"/>
        </p>   
        <p>
            Upload KML file: 
            <input type="file" name="datafile">
        </p>

        <input type="submit" name="submit" value="Save">

    </form>

    <hr>

    <form name="no_result_form" method="POST" action="" enctype="multipart/form-data">
        <p>
            No results text: 
            <input type="text" name="no_result" />
        </p>

        <input type="submit" name="submit" value="Save">

    </form>

    <!-- Insert table here -->


<?php
}