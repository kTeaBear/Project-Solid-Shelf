<?php

define ("idxQty","Qty");
define ("idxPrc","Prc");

function cartDefine()
{
  	$outCart = array();
  	// Set session variables
  	if(isset($_POST["gameCart"]))
  	{
  		unset($_POST["gameCart"]);
  	}
	return $outCart;
};


function cartLoad()
{
  $outCart	=  $_SESSION["gameCart"];
  return $outCart;
}

function cartSave(&$ioCart)
{
	$_SESSION["gameCart"] = $ioCart;
}

function cartAdd(&$ioCart, $inItemId, $inItemQuanity, $inItemAmount)
{
    if(array_key_exists($inItemId, $ioCart))
    {
     $ioCart[$inItemId][idxQty] += $inItemQuanity;
     echo "Item " . $inItemId . " exists and now has a quanity of  " . $ioCart[$inItemId][idxQty] ."<br>";
    }
    else
    {
     $ioCart[$inItemId][idxQty]= $inItemQuanity;
     $ioCart[$inItemId][idxPrc]= $inItemAmount;

    }
}

function cartReduceQty(&$ioCart, $inItemId, $inItemQuanity)
{
    if(array_key_exists($inItemId, $ioCart))
      {
    	$currQty = $ioCart[$inItemId][idxQty];
    	if ($currQty < $inItemQuanity)
    	   {
	      $ioCart[$inItemId][idxQty] = 0;
	      echo "Item " . $inItemId . " only had " . $currQty ."<br>" ;
    	   }
    	else
    	   {
    	      $ioCart[$inItemId][idxQty] -= $inItemQuanity;
    	   }

      echo "Item " . $inItemId . " now has a quanity of  " . $ioCart[$inItemId][idxQty] ."<br>";
      }
    else
      {
       echo "Item " . $inItemId . " not in cart <br>" ;
     }
}

function cartRemoveItem(&$ioCart, $inItemId)
{
    if(array_key_exists($inItemId, $ioCart))
      {
    	$currQty = $ioCart[$inItemId][idxQty];
    	unset($ioCart[$inItemId]);
	    echo "Item " . $inItemId . " with a quanity of  " . $currQty ." has been removed <br>" ;
      }
    else
      {
       echo "Item " . $inItemId . " not in cart <br>" ;
     }
}


function cartClear(&$ioCart)
{
	foreach ($ioCart as $key => $value)
	 {
	 	unset($ioCart[$key]);
	 }

	echo "Cart cleared <br>";
}
?>