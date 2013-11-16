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

    if(isset($_POST['text_result']) && isset($_POST['text_link']) && isset($_FILES['datafile'])) {
        
        $xml = simplexml_load_file($_FILES['datafile']['tmp_name']);
        $childs = $xml->Document->Folder->children();
        foreach ($childs as $child)
        {
            print_r($child->MultiGeometry->Polygon->outerBoundaryIs->LinearRing->coordinates);

        }





        // $target_path = "kml/";

        // $target_path = $target_path . $_FILES['datafile']['name']; 
        // if(move_uploaded_file($_FILES['datafile']['tmp_name'], $target_path)) {
        //     echo "<h3 style='color: green;'>The file ".  $_FILES['datafile']['name']. 
        //     " has been uploaded.</h3>";
        // } else{
        //     echo "<h3 style='color: red;'>There was an error uploading the file, please try again!</h3>";
        // }

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
            <input type="text" name="text_link" />
        </p>   
        <p>
            Upload KML file: 
            <input type="file" name="datafile">
        </p>

        <input type="submit" name="submit" value="Save">

    </form>

    <hr>

    <!-- Insert table here -->


<?php
}