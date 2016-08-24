


<?php

 session_start();

 $status = "";

 if(!isset($_SESSION['username'])){
 $status = "<a href='./login.php' title='Login/Register'><span style='color:red;'>Login/Register</span>";
 
 } else {

 $status = "<a href='./update.php' title='Update Profile' style='color:white;'>Welcome back, " . $_SESSION['username'] . "!</a><a href='./logout.php' title='Logout'><span style='color:red;'>Logout</span>";

 }

 //$status = "<span style='color:red;'><b>Welcome " . $_SESSION['username'] . "</b></span>";

 // import all database connection settings
  require '../../labs/db_conn.php';
 
 // Establish database connection using PDO
 $dbConn = new PDO("mysql:host=$host;dbname=$dbname", $username, $passwd);
 
 // Shows error when connecting to database
 $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$sort     = (isset($_POST['sort']) ? $_POST['sort'] : null);
$gameCart = (isset($_POST['gameCart']) ? $_POST['gameCart'] : null);

 // This is the only function we need to pull the games from the DB.
 // The SQL queries do our filtering and sorting.	 
 function getGames() {
    global $dbConn, $stat, $sort;  //it uses the variables declared previously
    //NOTE: field names MUST match the ones in database, they are case sensitive!
    $sql = "SELECT gameId,categoryId,title,description,maker,rentAmount,system,releaseDate,image,name
            FROM solidshelf_games g
            JOIN solidshelf_category c
            ON g.categoryId = c.Id
            ORDER BY " . $sort . " ASC";
            
    $stat = $dbConn -> prepare($sql);
    $stat -> execute();
    return $stat->fetchAll();
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
<link rel="stylesheet" href="/avendanoluciano/cst336/js/jquery-ui-1.11.2.custom/jquery-ui.theme.css">
<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="/avendanoluciano/cst336/js/jquery-ui-1.11.2.custom/jquery-ui.js"></script>         

<script>
$.fx.speeds._default = 800;
$(function() {
<?php	
if (isset($_POST['sort']) || $sort = "gameId"){
 $games = getGames();
 foreach ($games as $game){
  echo  "$( \".description".$game['gameId']."\" ).dialog({autoOpen: false,show: \"blind\",hide: \"explode\"});";
  echo  "$( \".opener".$game['gameId']."\" ).click(function() {\$( \".description".$game['gameId']."\" ).dialog( \"open\" ); return false;});";
 }
} else {$sort="gameId";} 
?>    
});
</script>

</head>

<body>
<div id="mainWrapper">
  <header> 
    <!-- This is the header content. It contains Logo and links -->
    
    <div id="headerLinks"><?php echo $status ?></a><a href="#" title="Cart">Cart</a></div>
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
  <div id="content">
    <nav class="sidebar"> 
      <!-- This adds a sidebar with 1 searchbox,2 menusets, each with 4 links -->
      Search:
      <input type="text"  id="search" value="search">
      
      <div id="menubar">
        <div class="menu">
          <h1>OtterDesignInc.</h1>
          <hr>
          <ul>
            <!-- List of links under menuset 1 -->
            <li><a href="/kuleckcaitlin/CST336/homepage.html" title="Caitlin Kuleck">Caitlin</a></li>
            <li><a href="/mitchellclarenceg/public_html/CST336/index.html" title="Clarence Mitchell">Clarence</a></li>
            <li><a href="/avendanoluciano/cst336/index.html" title="Luciano Avendano">Luciano</a></li>
            <li class="notimp"><!-- notimp class is applied to remove this link from the tablet and phone views --><a href="#"  title="Link">Link 4</a></li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="mainContent">
      <table>
      	<tr>
      		<td>
      			<form method="post" >
      			<span style="color: white;">Sort By:</span>
      			<select name="sort" onchange="this.form.submit()">
      				
      				<option value="gameId" selected="selected">Game Id</option>
      				<option value="name">Category</option>
      				<option value="rentAmount">Price</option>
      				<option value="system">System</option>
      			</select>
      			</form>
      		</td>
      		<td>
      			<span style="color: white;">Click on the image for more info.</span>
      		</td>
      	</tr>
      </table>	
      <div class="productRow">
      	<!-- Each product row contains info of 3 elements -->
        <!-- Each individual product description -->
          <?php
            if (isset($_POST['sort']) || $sort = "gameId"){
             $gameRow = getGames();
             //$output = array_slice($gameRow1, 0, 3, true);
			
                      foreach ($gameRow as $game){
                      	echo "<div class='productInfo'><div class='description".$game['gameId'] ."' title='".$game['title']."'><b>Description: </b>" . $game['description'] . "<br /><b>Category: </b>".$game['name']."<br /><b>Developer: </b>".$game['maker']."<br /><b>System: </b>".$game['system']."<br /><b>Release Date: </b>".$game['releaseDate']."</div><div><input type='image' class='opener" .$game['gameId']. "' style='height:100px;width:100px' src='/avendanoluciano/cst336/images/solid_images/". $game['image'] ."' /></div> <p class='price'> $". $game['rentAmount'] ."</p> <p class='productContent'>" . $game['gameId'] . " " . $game['title'] . "</p> <p class='productContent'> Category: " . $game['name'] . "</p> <p class='productContent'> System: " . $game['system'] . "</p> <input type='button' name='rent' value='Rent' class='buyButton'></div>";
					  }
			} else {
				// Else set the default sort by game id
				$sort = "gameId";
			}		  
          ?>         
      </div>
      

      
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
