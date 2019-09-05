<?php

/*  Museum php snippet */
/*
  Design
  0. Pass in the category on the link, ?category=1
  1. Build all items in divs in this category.
  2. 'Show' just one in php, rest 'hide'
  3. Js just hides all, then shows new one.
 */

/* Debug only
 */
error_reporting (E_ALL|E_STRICT);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 'on');


//  All Item Fetch
$query = new AirpressQuery();
$query->setConfig("WPMuseum");   // Airpress configuration name
$query->table("Imported table"); // Airtable base, default table name
$airdata = new AirpressCollection($query);

// Possible categories from Base
$categories = array( "Statues", "Paintings", "Carpets");

// Clean and validate link param, index to category array
$cat_get = intval(  array_key_exists('category', $_GET) ? $_GET["category"] : 0);

if (is_int($cat_get) and $cat_get >= 0 and $cat_get < count($categories)){
  $cat_sel = $categories[$cat_get];
}
else {
  $cat_sel = $categories[0];
}


// Build top Menu categories. Default is Statue, check it
$category_menu = '';
$cat_ix = 0;
foreach ($categories as $catitem){
  $catshow = ucfirst($catitem);
  $checked = $cat_sel == $catitem ? ' bolder ' : '';
  $link = "?category=".$cat_ix;

  $category_menu .=  '<a class="popo-category-link '.$checked.'" 
          href="'.$link.'">'.$catshow.'</a>';
  $cat_ix++;
}

$item_link_list = '';
$ar_of_items = array();

// Spin thru all the items, pull out items in this category
foreach($airdata as $item){
    // What category is this item in?
    $item_category = $item["category"];
    if (($item_category and $item_category == $cat_sel) ){
      $ar_img = $item["images"];
      if ($ar_img){
        // Display only the first image for each item
        // do not wrap this - js fails
        // $item_link_list .= '<img class="category-image" src="'. $ar_img[0]['url'] .'" id="'.$item["numéro unique"].'" ><br>';
        $item_link_list .= "<img class='popo-category-image' src='". $ar_img[0]["url"] ."' id='".$item["number"]."' ><br>";
    }

    // The data is too messy for json_encode. Manual copy pita
    // Escape single quotes, because I have to wrap with '' to put in js variable.
    $hash = array();
    $hash["nom"] = $item['title']; // ack, double spaces!
    $hash["cat"] = strtoupper($item['category']);
    $hash["inf"] = $item['info'];
    $hash["mat"] = $item['media'];
    $hash["art"] = $item['artist'];
    $hash["car"] = $item['dates'];
    $hash["leg"] = $item['dimensions'];
    $hash["num"] = strval($item['number']); // cast to string!
    $hash["ima"] = $item['images'];
    $ar_of_items[$hash["num"]] = $hash;  // Index with item id
  }
}

// ----------- Layout Start --------------

// ----------- Category menu --------------

echo '
  <div id="tab-2" class="tab-content ">
    <form id="category_form" action="" method="post">
  ';
      echo $category_menu;
      echo '
    </form>
  </div>';

echo '    <br>
          <div class="popo-wrapper">
            <div id="popo-list" class="popo-list"> ';
echo          $item_link_list;
echo '      </div>
';


// ----------- Loop through hidden divs --------------
$counter = 0;
foreach($ar_of_items as $row){

  // Show first item
  $showme = $counter ? '' : 'style="display:block"';
  $counter++;

// Item section wrapper to show/hide
echo '
            <div class="popo-left" '.$showme.' id="left'. $row['num'] .'" >
';

  // Item Info
  echo '<h1 id="popid-nom">'.$row['nom'].'</h1>';
  


  echo '<div id="popid-cat"><b>'.$row['art'].'</b></div>';
 
  echo '<br>';   
  echo '<div id="popid-inf">'.$row['inf'].'</div>';
  echo '<br>';
 
  echo '<span class="popo-info-title">Medium:</span> 
        <span id="popid-mat"> '.$row['mat'].'</span><br>';
  
  
 

  echo '<span class="popo-info-title">Dates:</span> 
        <span id="popid-car"> '.$row['car'].'</span><br>';
  
  echo '<span class="popo-info-title">Dimensions:</span> 
        <span id="popid-leg"> '.$row['leg'].'</span><br>';
  
  echo '<span class="popo-info-title">Catalogue #</span> 
        <span id="popid-num"> '.$row['num'].'</span><br>';
  
  echo '
            </div> <!-- end div left -->
            <div class="popo-right" '.$showme.' id="right'. $row['num'] .'" >
  ';


  // Item images. Just one big one, give it an ID for changing onClick. Using alt as a hack
  $ar_img = $row["ima"];
  if ($ar_img){
    echo '<div class="popo-right-image">';
    echo '  <img class="popo-right-image-big" id="pic'. $counter. '" src="' . $ar_img[0]["url"] . '">';
    echo '</div>';
  }
  // and thumbnails.   Use html5 data- for custom attribute  NOPE DIDN"T WORK
  if (count($ar_img) > 1) {
    for ($ix = 0; $ix < count($ar_img); $ix++) {
      echo '<div class="popo-thumb">' ;
      echo '  <img class="popo-right-image-thumb" src="' . $ar_img[$ix]["url"] . '" alt="pic'.$counter.'">';
      echo '</div>';
    }
  }

echo '
            </div> <!-- end div right -->
';

} // end ar_of_items loop

echo '
          </div> <!-- end div popo-wrapper -->
';


