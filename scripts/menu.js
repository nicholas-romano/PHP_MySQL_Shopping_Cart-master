//menu.js

var isShowing = false; /* Flag to indicate if a drop-down menu is visible */
var menuItem = null; /* Reference to a drop-down menu */

//Show the drop-down menu with the given id, if it exists, and set flag  
function show(id)
{
    hide(); /* First hide any previously showing drop-down menu */
    menuItem = document.getElementById(id);
    if (menuItem != null)
    {
        menuItem.style.visibility = 'visible';
        isShowing = true;
    }
}

//Hide the currently visible drop-down menu and set flag
function hide()
{       
    if (isShowing) menuItem.style.visibility = 'hidden';
    isShowing = false;
}
