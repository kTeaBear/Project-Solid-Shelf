


<?php

 session_start(); // start/initiate new php session 

 $status = "";
 $today  = date("Y-m-d H:i:s");
 
 $username    = (isset($_POST['username']) ? $_POST['username'] : null);
 $usernameR   = (isset($_POST['usernameR']) ? $_POST['usernameR'] : null);

 $lastName    = (isset($_POST['lastName']) ? $_POST['lastName'] : null);
 $firstName   = (isset($_POST['firstName']) ? $_POST['firstName'] : null);
 $password    = (isset($_POST['password']) ? $_POST['password'] : null);
 $email       = (isset($_POST['email']) ? $_POST['email'] : null);


  // Add some input validation, validate all user input.
  function validator() {
    global $usernameR, $lastName, $firstName, $password, $email, $status;

  if(!$firstName || !preg_match("/^([a-zA-Z]{2,}(?: [a-zA-Z]{0,})*)$/", $firstName)) {
        $status .= "<span style=\"color: #f00\">ERROR: You must enter a valid First Name.</span> <br />";
        $invalidFirst = "class='req'";
  }

  //This regular expression checks the Last Name entry for letter a-zA-Z characters and makes sure
  //at least two characters are entered, spaces are acceptable.
  if(!$lastName || !preg_match("/^([a-zA-Z]{2,}(?: [a-zA-Z]{0,})*)$/", $lastName)) {
        $status .= "<span style=\"color: #f00\">ERROR: You must enter a valid Last Name.</span> <br />";
        $invalidLast = "class='req'";
  }

  //This regular expression checks the Username entry for letter a-zA-Z0-9 characters and makes sure
  //at least two characters are entered, spaces are acceptable.
  if(!$usernameR || !preg_match("/^([a-zA-Z0-9]+[a-zA-Z0-9_-\s]+)$/", $usernameR)) {
        $status .= "<span style=\"color: #f00\">ERROR: You must enter a valid Username.</span> <br />";
        $invalidUN = "class='req'";
  }

  //This regular expression checks the Email entry for alphanumeric characters, @ symbol, and periods are used
  if (!$email || !preg_match("/^[\.0-9a-z_-]{1,}@[\.0-9a-z-]{1,}\.[a-z]{1,}$/si", $email)) {
        $status .= "<span style=\"color: #f00\">ERROR: You must enter a valid Email Address (i.e. jsmith@yahoo.com)</span> <br />";
        $invalidEmail = "class='req'";
  }

  //This regular expression checks the password entry for alphanumeric characters and makes sure
  //at least two characters are entered, spaces are acceptable.
  if(!$password){
        $status .= "<span style=\"color: #f00\">ERROR: You must enter a valid password. Alphanumeric Only.</span> <br />";
        $invalidPass = "class='req'";
  }

  if (!$status) {
   // Add match details into database according to form parameters
   if ((isset($_POST["signup"])) == "SignUp") { //checks whether the "addMatch" button was clicked
     // import all database connection settings
     require '../../labs/db_conn.php';

     // Establish database connection using PDO
     $dbConn = new PDO("mysql:host=$host;dbname=$dbname", $username, $passwd);

     // Shows error when connecting to database
    $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $customerId = $dbConn->lastInsertId();

    $sql = "INSERT INTO solidshelf_customer
             (customerId, firstName, lastName, username, password, email)
             VALUES
             (:customerId, :firstName, :lastName, :username, :password, :email)";

    $stat = $dbConn -> prepare($sql);
    $stat -> execute ( array (":customerId"    => $customerId,
                              ":firstName"     => $_POST['firstName'],
                              ":lastName"      => $_POST['lastName'],
                              ":username"      => $_POST['usernameR'],
                              ":password"      => hash('sha1', $_POST['password']),
                              ":email"         => $_POST['email']));
	 
	$newUser =  $_POST['usernameR'];
    //$status = "<span style='color:red;'><b><i>Welcome to Solid Shelf Video Game Rentals " .$username. "!<b></span>";
    $_SESSION['username'] = $newUser;
    header("Location: index.php");
    }

  }
  return $status;
 }
  
  //If the Submit Button is clicked validate each required field using the above "if" statements.
  if ((isset($_POST["signup"])) == "SignUp"){
          $status = validator();
  } else {
          $status = "<span style='color:red;'><b>Please login or create and account. Required fields are denoted with a *</b></span>";
    }



// This POST if for users with established acounts
if (isset($_POST['username'])){
 // import all database connection settings
 require '../../labs/db_conn.php';

 // Establish database connection using PDO
 $dbConn = new PDO("mysql:host=$host;dbname=$dbname", $username, $passwd);

 // Shows error when connecting to database
 $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

 $sql = "SELECT *
         FROM solidshelf_customer
         WHERE username = :username
         AND password = :password";

 $stat = $dbConn -> prepare($sql);
 $stat -> execute(array(":username" => $_POST['username'], ":password" => hash("sha1", $_POST['password'])));
 $record = $stat -> fetch();
 // If the user doesnt exist or incorrect pw display error message.
 if (empty($record)){

  $status = "<span style='color:red;'><b>Wrong Username/Password!</b></span>";

 } else {
   $logId = $dbConn->lastInsertId();	
   $sql = "INSERT INTO solidshelf_log
           (logId, username, timeIn)
           VALUES
           (:logId, :username, :timeIn)";
		   
   $stat = $dbConn -> prepare($sql);
   $stat -> execute ( array (":logId"    => $logId,
                             ":username" => $_POST['username'],
                             ":timeIn"   => $today));		   
			 	
   $_SESSION['username'] = $record['username'];
   header("Location: index.php");
  }
}


?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Solid Shelf - Video Game Rentals</title>
<link href="/avendanoluciano/cst336/css/solid_shelf.css" rel="stylesheet" type="text/css">

<!--The following script tag downloads a font from the Adobe Edge Web Fonts server for use within the web page. We recommend that you do not modify it.-->
<script>var __adobewebfontsappname__="dreamweaver";</script><script src="http://use.edgefonts.net/montserrat:n4:default;source-sans-pro:n2:default.js" type="text/javascript"></script>

<style>
  table.center {
    margin-left:auto;
    margin-right:auto;
  }
  .req{
      border: 1px solid red;
  }
</style>

</head>

<body>
<div id="mainWrapper">
  <header> 
    <!-- This is the header content. It contains Logo and links -->
    
    <div id="headerLinks"><a href="#" title="Login/Register">Login/Register</a><a href="#" title="Cart">Cart</a><a href="./index.php" title="Home">Home</a></div>
  </header>
  <section id="offer"> 
  	<!-- The offer section displays a banner text for promotions -->
  	<table style="text-align: left; line-height: 10%;">
  		<tr>
  			<td>
  				<img src="/avendanoluciano/cst336/images/LOGO.png" style="width:100px; height:110px">
  			</td>
  			<td>
  				 <h1>SolidShelf Game Rentals</h1>
                 We are guaranteed to beat any online competitor!
  			</td>
  		</tr>
  	</table>
  </section>


    <div class="mainContentLogin">
     
     <div class="center">
       <table>
        <tr>
         <td>
          <span style="color:red">Sign Up</span>
         </td>
         <td>
          <span style="color:red">Sign In</span>
         </td>
        </tr>

        <tr>
         <td colspan="2" style="text-align: center;">
                        <?php echo $status ?>
                        <br /><br />
         </td>
        </tr>

        <tr>
         <td>
         <form method="post"> <!-- Start Form -->
          <table style=" color:white; border: dotted 1px silver; text-align: center;" class="center">
          <tr>
           <td>
           <label for="FirstName">
            First Name*
            </label>
           </td>
           <td>
            <input maxlength="80" type="text" <?php print $invalidFirst; ?> name="firstName" value="<?php print $firstName;?>">
           </td>
          </tr>
          <tr>
           <td>
            <label for="LastName">
             Last Name*
            </label>
           </td>
           <td>
            <input maxlength="80" type="text" <?php print $invalidLast; ?> name="lastName" value="<?php print $lastName;?>">
           </td>
          </tr>
          <tr>
           <td>
            <label for="UserNameRegister">
             UserName*
            </label>
           </td>
           <td>
            <input maxlength="80" type="text" <?php print $invalidUN; ?>  name="usernameR" value="<?php print $usernameR;?>">
           </td>
          </tr>
          <tr>
           <td>
            <label for="Email">
             Email*
            </label>
           </td>
           <td>
            <input maxlength="80" type="text"  name="email" <?php print $invalidEmail; ?> value="<?php print $email;?>" >
           </td>
          </tr>
           <td>
            <label for="Pass">
             Password*
            </label>
           </td>
           <td>
            <input maxlength="80" type="password"  name="password" <?php print $invalidPass; ?> >
           </td>
          </tr>
         
          </table>
           <table class="center">
                <tr>
                    <td>
                        <input type="submit" name="signup" value="SignUp" />
                    </td>
                </tr>
           </table>
         </form>
        </td>

        <td>

           <form method="post"> <!-- Start Form -->
             <table style=" color:white; border: dotted 1px silver; text-align: center;" class="center">

              <tr >
                 <td>
                  Username:
                 </td>
                 <td >
                    <input type="text" name="username" />
                 </td>
              </tr>
              <tr>
                 <td>
                  Password:
                 </td>
                 <td >
                    <input type="password" name="password" />
                 </td>
              </tr>
              <tr>
                <td>
                    &nbsp;
                </td>
              </tr>
              <tr>
                <td>
                    &nbsp;
                </td>
              </tr>
             </table>
             <table class="center">
                <tr>
                    <td>
                        <input type="submit" value="Login" />
                    </td>
                </tr>
             </table>
             </form> <!-- End Form -->

        </td>      
       </tr>
      </table>
	 </div>
      

      
    </div>
    <footer>
    <!-- This is the footer with default 3 divs -->
    <div> <p>Guard: “I used to be an adventurer like you … then I took an arrow in the knee.”</p></div>
    <div> <p>Dom: “Did you hear that? What the hell’s that sound?”<br />
             Marcus: “It’s just the wind.”<br />
             Dom: “Yeah, right. When was the last time the wind said ‘hostiles’ to you?”</p> </div>
    <div> <p style="color:red;" >Otter-Design-Inc. Solid Shelf Games 2015</p></div>
  </footer>
 
</div>
</body>
</html>
