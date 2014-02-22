<?
function open_db($dbName)
{
 global $fp_db;
 global $reverseHeaders_db;
 global $headers_db;

 $headers_db=null;
 $fp_db = fopen ($dbName,"r");
 if ($fp_db)
  {
  $headers_db = fgetcsv ($fp_db, 1000, ",");
  $reverseHeaders_db=array();
  $headersLen=count($headers_db);
  for ($i=0;$i<$headersLen;$i++)
   {
   $reverseHeaders_db[$headers_db[$i]]=$i;
   }
  }
}

function getLine_db()
{
 global $fp_db;
 global $data_db;
 if ($fp_db)
  {
  $data_db = fgetcsv ($fp_db, 1000, ",");
  return $data_db;
  }
 return 0;
}

function getItemOffset_db($itemName,$offset=0)
{
 global $data_db;
 global $reverseHeaders_db;

 if ($data_db)
  {
  return $data_db[$reverseHeaders_db[$itemName]+$offset];
  }
 return 0;
}

function getItem_db($itemName)
{
 return getItemOffset_db($itemName);
}

function getArrayFrom_db($itemName)
{
 $retArray=array();

 global $data_db;
 global $reverseHeaders_db;
 
 if ($data_db)
  {
  $totalCount = count($data_db);
  for ($offset = $reverseHeaders_db[$itemName]; $offset<$totalCount ; $offset++)
   $retArray[] = $data_db[$offset];
  }
 return $retArray;
}

function close_db()
{
 global $fp_db;
 if ($fp_db)
  {
  fclose($fp_db);
  $fp_db=0;
  }
}

function start_write_db($dbName, $dbHeaders)
{
 global $fp_db;
 global $headers_db;
 global $reverseHeaders_db;

 $headers_db=$dbHeaders;
 $reverseHeaders_db=array();
 $headersLen=count($headers_db);
 for ($i=0;$i<$headersLen;$i++)
  {
  $reverseHeaders_db[$headers_db[$i]]=$i;
  }

 $fp_db = fopen ($dbName,"w");
 if ($fp_db)
  {
  $buffer="";
  reset ($dbHeaders);
  $numHeaders=0;
  while ($c=each($dbHeaders))
   {
   $numHeaders++;
   if ($buffer) $buffer.=",";
   $buffer.="\"".$c[1]."\"";
   }
  fwrite ($fp_db, $buffer."\r\n");
  }
}

function write_line_db($data)
{
 global $fp_db;
 global $reverseHeaders_db;
 global $headers_db;
 if ($fp_db)
  {
  $buffer="";
  reset ($data);
  while ($c=each($data))
   {
   if ($buffer) $buffer.=",";
   $buffer.="\"".$c[1]."\"";
   }
  fwrite ($fp_db, $buffer."\r\n");
  }
}

function createNewItem_db($reverseHeaders, $newId)
{
 $retVal=array_pad(array(),sizeof($reverseHeaders),"");
 $retVal[$reverseHeaders["ID"]]=$newId;
 $retVal[$reverseHeaders["New"]]=1;
 $retVal[$reverseHeaders["Deleted"]]=0;
 $retVal[$reverseHeaders["Changed"]]=1;

 return $retVal;
}

function stringDateToArray($stringDate)
{
 if (!eregi("([0-9]+)[\/\.\:]([0-9]+)[\/\.\:]([0-9]+)",$stringDate,$splitDate))
  {
  echo "<p>Error in stringDateToArray() function in db-funcs.php</p>";
  return 0;  // error.
  }

 $retArray=array();

 $retArray["year"] = (int)$splitDate[3];
 if ($retArray["year"] < 90)
  $retArray["year"] += 2000;
 else if ($retArray["year"] < 100)
  $retArray["year"] += 1900;

 $retArray["month"] = (int)$splitDate[2];
 $retArray["day"] = (int)$splitDate[1];

 return $retArray;
}

function compareDates($a, $b)
{
$aDate = stringDateToArray($a);
$bDate = stringDateToArray($b);
if (!$aDate || !$bDate)
 {
 echo "<p>Error in function compareDates in db-funcs.php</p>";
 return 0;
 }

if ($aDate["year"] < $bDate["year"])
 return -1;
if ($aDate["year"] > $bDate["year"])
 return 1;

if ($aDate["month"] < $bDate["month"])
 return -1;
if ($aDate["month"] > $bDate["month"])
 return 1;

if ($aDate["day"] < $bDate["day"])
 return -1;
if ($aDate["day"] > $bDate["day"])
 return 1;

return 0;
}

function compareDate($stringDate, $nowDateStamp=0)
 {
 $compareDate=stringDateToArray($stringDate);
 if (!$compareDate)
  {
  echo "<p>Error in compareDate() function in db-funcs.php</p>";
  return 0;  // error.
  }

 if (!$nowDateStamp)
  $nowDateStamp=time();

 $nowDate = getdate($nowDateStamp);

 $nowDateYear = (int)$nowDate["year"];
 $nowDateMonth = (int)$nowDate["mon"];
 $nowDateDay = (int)$nowDate["mday"];

// echo $nowDateDay."/".$nowDateMonth."/".$nowDateYear."<br>";

// echo $compareDateDay."/".$compareDateMonth."/".$compareDateYear."<br>";

 if ($compareDate["year"] < $nowDateYear)
  return -1;
 if ($compareDate["year"] > $nowDateYear)
  return 1;

 if ($compareDate["month"] < $nowDateMonth)
  return -1;
 if ($compareDate["month"] > $nowDateMonth)
  return 1;

 if ($compareDate["day"] < $nowDateDay)
  return -1;
 if ($compareDate["day"] > $nowDateDay)
  return 1;

 return 0;
 }

function isDateFuture($stringDate, $nowDate=0)
 {
 if (compareDate($stringDate, $nowDate)>0)
  return 1;
 return 0;
 }

function isDatePast($stringDate, $nowDate=0)
 {
 if (compareDate($stringDate, $nowDate)<0)
  return 1;
 return 0;
 }
 
 function addHouseScore($housename) {
		global $houselist; global $totalPoints;
		$position=getItem_db($housename);
		if ($position && $position!="" && $position!=0) {
			$numHouses=count($houselist);
			$numTies=0;
			for ($i=0;$i<$numHouses;$i++) {
				if ($houselist[$i]!=$housename && $position==getItem_db($houselist[$i])) {
					$numTies++;
					}
				}
			if ($numTies>0) {
				$position += ($numTies/2);
				}
			$totalPoints[$housename]+=(7-$position);
			}
		}	
?>