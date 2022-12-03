<?php
/*/
|author     Keelan Hyde
|group      Y3S Group
|date       2022-02-24
|file       contentlib.php
|brief      Library that contains functions for grabbing articles and endeavour issues
|notes      NONE
|
|ToDo       NONE
/*/


/**
 * Gets Article or Endeavour content from database
 * @param string $table Takes the name of the content table you wish to access
 * @param string|null $category Takes the ID number of the category you wish to display. Enter NULL if accessing the Article dbTable
 * @param string|null $subcategory Takes the ID of the subcategory you wish to display. Enter NULL if accessing the Article dbTable
 * @param string|null $order Takes 'new' for newest first or 'old' for oldest first, or 'rand' for random ordering, or NULL for no ordering
 * @param bool $featured Takes TRUE if requesting featured articles, otherwise FALSE for all other content. (ONLY WITH ARTICLE TABLE)
 * @param int $limit Takes an integer to indicate how many articles to take return. (ONLY WITH ARTICLE TABLE)
 * @return object Returns array of records that matched query
 */
function getContent($table, $category, $subcategory, $order, $featured, $limit){
	include 'dbConn.php';

	$sql = "SELECT * FROM $table";	//Base sql query
	
	//Used for articles in a specific category or subcategory
	if($category != NULL && $subcategory == NULL){	//For category only selection
		$sql = $sql . " INNER JOIN ArticleCategory ON $table.Article_ID = ArticleCategory.Article_ID WHERE ArticleCategory.Cat_ID='$category'";
	}else if($category != NULL && $subcategory != NULL){	//For category & subcategory selection
		$sql = $sql . " INNER JOIN ArticleCategory ON $table.Article_ID = ArticleCategory.Article_ID WHERE ArticleCategory.Cat_ID='$category' AND ArticleCategory.SubCat_ID='$subcategory'";
	}

	//Selects the ordering by date created
	if($order=="new"){	//Newest articles first
		$sql = $sql . " ORDER BY $table.Created_Date DESC";
	}else if($order=="old"){	//Oldest articles first
		$sql = $sql . " ORDER BY $table.Created_Date ASC";
	}else if($order=="rand"){
		$sql = $sql . " ORDER BY Rand()";	//Random order
	}else{
		//RETURN ERROR
	}

	//Selects featured articles
	if($featured){
		$sql = $sql . " WHERE Featured='1'";
	}

	//Selects $limit amount of records.
	if($limit != NULL && $limit > 0){
		$sql = $sql . " LIMIT $limit";	//Limits records returned to value given
	}

	//Queries database
	if($results = $conn->query($sql) or die($conn->error)){
		$rtnData = [];
		while($row = $results->fetch_assoc()){
			array_push($rtnData,$row);
		}
		return $rtnData;	//Successful query
	}else{
		//ERROR CODE HERE
	}

}

/**
 * Randomly fetches articles from user's prefered categories if a user is not logged in then it selects a random article
 * @return array Returns the four random articles to be displayed
 */
function getUserPrefCategory(){
	include 'dbConn.php';

	$limitDate = date('Y-m-d H:i:s', strtotime('-6 month'));	//Limits returned content to newer than 6 months
	$rtnData = [];	//Return array
	$loggedIn = NULL;	//Selects appropriate SQL query for Registered users or Guests

	//Checks for registerd users or Guests
	if($_SESSION){
		$loggedIn = TRUE;
	}else{
		$loggedIn = FALSE;
	}


	if($loggedIn){
	
		//Query request for Registered users
		$sql = "SELECT Article.* FROM CatPref INNER JOIN ArticleCategory ON CatPref.SubCat_ID = ArticleCategory.SubCat_ID
				AND CatPref.Cat_ID = ArticleCategory.Cat_ID
				INNER JOIN Article ON Article.Article_ID = ArticleCategory.Article_ID 
				WHERE Article.Created_Date >= '$limitDate' AND CatPref.Usr_ID = '{$_SESSION['usrID']}' 
				ORDER BY Rand() 
				LIMIT 4";
				
		if($result = $conn->query($sql)){

			//Adds each record to the rtndata array
			while($row = $result->fetch_assoc()){
				array_push($rtnData,$row);
			}

		}else{

			//Selects from available articles if user has no preferrences set (TEMPORARY) [ADD]-CHECK FOR SET PREFERENCES
			$sql = "SELECT Article.* FROM Article
				WHERE Article.Created_Date >= '$limitDate'
				ORDER BY Rand() 
				LIMIT 4";
				
			if($result = $conn->query($sql)){

				//Adds each record to the rtndata array
				while($row = $result->fetch_assoc()){
					array_push($rtnData,$row);
				}

			}else{
				die($result->error);
			}
		}
		
	}else{

		//Selects from available articles for Guests
		$sql = "SELECT Article.* FROM Article
				WHERE Article.Created_Date >= '$limitDate'
				ORDER BY Rand() 
				LIMIT 4";
				
		if($result = $conn->query($sql)){

			//Adds each record to the rtndata array
			while($row = $result->fetch_assoc()){
				array_push($rtnData,$row);
			}

		}else{
			die($result->error);
		}

	}

	return $rtnData;
}

?>
