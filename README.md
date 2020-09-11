<h1>Applications for rendering arbitrary trees</h1>
 <p>The "create root" button creates the root of the tree.
     The "root" has two buttons: "add a node" (in the form of a "+" sign)
     and "remove node" (in the form of a "-")<br>
 When you click on "+", a new node is added, to the right and below, relative to
 next to which the button was pressed. By clicking on "-" - the node will be deleted with all
 nested nodes.<br>
Node data is stored in the database.<br>
 When deleting a node, a modal window pops up to confirm the action with a 20 second timer.
 After the time the modal pops up, it will close and the root or node will not be deleted.
 </p>
 <p>Application structure</p>
 <ul>
    <li><b>app</b> - the folder where the main files for working with the application are located
    <ul>
        <li><b>DB.php</b> - A model for establishing a connection with a database, preparing queries and receiving certain data from a database</li>
        <li><b>main.php</b> - Helper that determines the type of request received from the client side of the application</li>
        <li><b>TreeController</b> - a controller that calls a specific method that matches the type of request received from the client side of the application</li>
        <li><b>TreeElement</b> - class that executes database queries</li>
    </ul>
    </li>
    <li><b>assets</b> - the folder where the included styles and JS files are located</li>
    <li><b>composer.json</b> - sets the application namespace</li>
    <li><b>config.php</b> - configuration file for connecting to the database</li>
    <li><b>index.php</b> - the main file that serves the interaction of the client with the application</li>
 </ul>