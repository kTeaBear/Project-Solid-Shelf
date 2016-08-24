


<?php

 session_start(); // start/initiate new php session
 $status = "";
 $status2= "";
 
 if(!isset($_SESSION['username'])){
 header("Location: login.php");
 } else {

 $status2 = "<span style='color:white;'>" . $_SESSION['username'] . ", Is a MLG Pro!</span><a href='./logout.php' title='Logout' style='color:red;'>Logout</a>";

 }

 // import all database connection settings
 require '../../labs/db_conn.php';

 // Establish database connection using PDO
 $dbConn = new PDO("mysql:host=$host;dbname=$dbname", $username, $passwd);

 // Shows error when connecting to database
 $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

 $usernameR   = (isset($_POST['usernameR']) ? $_POST['usernameR'] : null);
 $customerId  = (isset($_POST['customerId']) ? $_POST['customerId'] : null);
 $lastName    = (isset($_POST['lastName']) ? $_POST['lastName'] : null);
 $firstName   = (isset($_POST['firstName']) ? $_POST['firstName'] : null);
 $password    = (isset($_POST['password']) ? $_POST['password'] : null);
 $email       = (isset($_POST['email']) ? $_POST['email'] : null);
 $invalidFirst= "";
 $invalidLast = "";
 $invalidUN   = "";
 $invalidEmail= "";
 $invalidPass = "";


  // Let's get some customer information to update
  function getCustomer($username){
   global $dbConn;

   $sql = "SELECT *
           FROM solidshelf_customer
           WHERE username = :username";

   $stat = $dbConn -> prepare($sql);
   $stat -> execute(array(":username"=>$username));
   return $stat->fetch();
  }

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
   if ((isset($_POST["update"])) == "Update") { //checks whether the "addMatch" button was clicked
     // import all database connection settings
     require '../../labs/db_conn.php';

     // Establish database connection using PDO
     $dbConn = new PDO("mysql:host=$host;dbname=$dbname", $username, $passwd);

     // Shows error when connecting to database
    $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $customerId = $dbConn->lastInsertId();

    $sql = "UPDATE solidshelf_customer
             SET firstName = :firstName,
                 lastName  = :lastName,
                 username  = :username,
                 password  = :password,
                 email     = :email
             WHERE customerId = :customerId";


    $stat = $dbConn -> prepare($sql);
    $stat -> execute ( array (":customerId"    => $_POST['customerId'],
                              ":firstName"     => $_POST['firstName'],
                              ":lastName"      => $_POST['lastName'],
                              ":username"      => $_POST['usernameR'],
                              ":password"      => hash('sha1', $_POST['password']),
                              ":email"         => $_POST['email']));
	 
	$newUser =  $_POST['usernameR'];
    $_SESSION['username'] = $newUser;
    $status = "<span style='color:white;'><b>Profile Updated! Let's go rent some games," .$_SESSION['username']. "</b></span>"; 
    $status2 = "<span style='color:white;'>" . $_SESSION['username'] . ", Is a MLG Pro!</span><a href='./logout.php' title='Logout' style='color:red;'>Logout</a>";
    }

  }
  return $status;
 }
  
  //If the Submit Button is clicked validate each required field using the above "if" statements.
  if ((isset($_POST["update"])) == "Update"){
          $status = validator();
  } else {
          $status = "<span style='color:white;'><b>Profile Update. Modify any fields necessary and click the Update button.</b></span>";
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
    
    <div id="headerLinks"><?php echo $status2; ?><a href="#" title="Cart">Cart</a><a href="./index.php" title="Home">Home</a></div>
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
       <table clas="center">
        <tr>
         <td colspan="2" style="text-align: center;">
                        <?php echo $status ?>
                        <br /><br />
         </td>
        </tr>
       </table>
       <table class="center">
        <?php $userInfo = getCustomer($_SESSION['username']); ?>
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
            <input maxlength="80" type="text" <?php print $invalidFirst; ?> name="firstName" value="<?=$userInfo['firstName']?>">
           </td>
          </tr>
          <tr>
           <td>
            <label for="LastName">
             Last Name*
            </label>
           </td>
           <td>
            <input maxlength="80" type="text" <?php print $invalidLast; ?> name="lastName" value="<?=$userInfo['lastName']?>">
           </td>
          </tr>
          <tr>
           <td>
            <label for="UserNameUpdate">
             UserName*
            </label>
           </td>
           <td>
            <input maxlength="80" type="text" <?php print $invalidUN; ?>  name="usernameR" value="<?=$userInfo['username']?>">
           </td>
          </tr>
          <tr>
           <td>
            <label for="Email">
             Email*
            </label>
           </td>
           <td>
            <input maxlength="80" type="text"  name="email" <?php print $invalidEmail; ?> value="<?=$userInfo['email']?>" >
           </td>
          </tr>
          <tr>
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
                        <input type="submit" name="update"     value="Update" />
                        <input type="hidden" name="customerId" value="<?=$userInfo['customerId']?>" />
                    </td>
                </tr>
           </table>
         </form>
        </td>

       </tr>
      </table>
	 </div>
      

      
    </div>
    <footer>
    <!-- This is the footer with default 3 divs -->
    <div> <p style="color:red;text-align:center;" >Otter-Design-Inc. Solid Shelf Games 2015</p></div>
    </footer>
 
</div>
</body>
</html>
