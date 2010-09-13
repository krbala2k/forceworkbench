<?php
require_once 'session.php';
require_once 'shared.php';

//Has the user selected a default object and clicked one
//of the action buttons. If so, proceed to that page; otherwise,
//show the form to do so.

if (isset($_POST['actionJump']) && $_POST['actionJump'] != "") {
    header("Location: $_POST[actionJump]");
} elseif (isset($_POST['select'])) {
    include_once 'header.php';
    displayError("Choose an object and an action to which to jump.");
    displaySelechForm();
    include_once 'footer.php';
}

else {
    include_once 'header.php';
    displaySelechForm();
    include_once 'footer.php';
}

function displaySelechForm() {
    ?>
<script>
    
    function toggleObjectSelectDisabled() {
        var usesObject = new Array();
        <?php
        foreach ($GLOBALS["MENUS"] as $menu => $pages) {
            foreach ($pages as $href => $page) {
                if ($page->onMenuSelect === 'usesObject') {
                    print "usesObject['$href'] = '$href';\n";
                }
            }
        }
        ?>
    
        var actionJumpVal = document.getElementById('actionJump').value;

        if (usesObject[actionJumpVal] != undefined) {
            document.getElementById('default_object').disabled = false;        
        } else {
            document.getElementById('default_object').disabled = true;            
        }
    }
    </script>
        <?php

        try {
            print "<form method='post' action='$_SERVER[PHP_SELF]'>\n";
            print "<p><strong>Select a default object and action:</strong></p>\n";

            //Display a list of actions as submit buttons. Jump to the selected
            //action's page on refresh (see IF statement at top)
            print "<p><strong>Jump to: </strong>" .
          "<select name='actionJump' id='actionJump' style='width: 20em;' onChange='toggleObjectSelectDisabled();'>" .     
          "<option value='select.php'></option>";
            foreach ($GLOBALS["MENUS"] as $menu => $pages) {
                foreach ($pages as $href => $page) {
                    if($page->onMenuSelect) print "<option value='" . $href . "'>" . $page->title . "</option>";
                }
            }
            print "</select></p>";


            //Describe a list of all the objects in the user's org and display
            //in a drop down select box
            print "<p><strong>Object: &nbsp; </strong>";
            printObjectSelection($_SESSION['default_object'],'default_object');


            print "<p/><input type='submit' name='select' value='Select' />";
            print "</form>\n";
        } catch (Exception $e) {
            displayError($e->getMessage(),false,true);
        }

        print "<script>toggleObjectSelectDisabled();</script>";
}

?>
