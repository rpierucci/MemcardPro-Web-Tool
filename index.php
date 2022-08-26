<html>
<link rel="stylesheet" type="text/css" href="style.css">
<head>
  <title>
    Memcard Pro Web Tools
  </title>
</head>
<body>

<form method="post">
    <input type="submit" name="refreshGameList"
            class="button" value="Refresh Game List" />

    <br><br>
    <input type="text" id="selectedGameId"
            name="selectedGameId" />
        
    <input type="submit"  name="readGameCards"
            class="button" value="Read Selected Game Cards" />

</form>

<pre>
<?php
//Get Relative Memory Card Path in root of program folder and open directory
$directoryPath = __DIR__ . "/MemoryCards";
$dh = opendir($directoryPath);

$masterList = array();

//Read /MemoryCards directory and gather cards, game information, etc. Store in masterList array in following structure:
//  MasterList {
//    Game Code {
//     Name : Game Name,
//     Memory Card 1: Byte Contents,
//     Memory Card 2: Byte Contents,
//     ...
//    } ...

while (($gameCode = readdir($dh)) !== false) {
  if($gameCode != "." && $gameCode != "..") {
    $gdh = opendir($directoryPath . "/" . $gameCode);
    $gameName = "";
    $gameArray = array();

    while(($file = readdir($gdh)) !== false) {
      if (strpos($file, ".txt") !== false) {
        $gameName = substr($file, 0, strpos($file, "."));
        $gameArray['Name'] = $gameName;
      }
      else
      {
        $fileName = $file;
        if($fileName != "." && $fileName != "..") {
          $fileContents = file_get_contents($directoryPath . "/" . $gameCode . "/" . $fileName);
          $gameArray[$fileName] = $fileContents;
        }
      }
    }
    $masterList[$gameCode] = $gameArray;
  }
}

function printGameList($masterList) {
  foreach($masterList as $gameCode => $value) {
    echo '<a href="#" onclick="document.getElementById(\'selectedGameId\').value=\'', $gameCode, '\';">', $gameCode, " ", $value['Name'], '</a><br />';
  }
}

function readGameCards($masterList, $selectedGameId) {
  $gameCards = $masterList[$selectedGameId];
  foreach($gameCards as $fileName => $value) {
    if($fileName != "Name") {
      $pathURL = "MemoryCards/" . $selectedGameId . "/" . $fileName;
      echo "<b>FileName : ", "<a href=" . $pathURL . " >", "$fileName", "</a></b>\n\n";
      $counter = 0;
      for ($x = 0; $x <= 14; $x++) {
        // Save Name
        $counter = $counter+8192;
        $block = substr($value, $counter+4, 64);
        $convert = mb_convert_encoding($block, 'UTF-8', "SJIS-win");
        
        // Block Count
        $blockCount = substr($value, $counter+3, 1);
        $blockCount = unpack('c*', $blockCount);
        
        //Icon Palette
        $iconImage = imagecreatetruecolor(16, 16);
        $paletteBytes = substr($value, $counter+96, 32);
        $palette = array();
        $colorCounter = 0;
        for ($i = 0; $i < 32; $i+=2) {
          $redChannel = (unpack('c*', $paletteBytes[$i+0])[1] & 0x1F) << 3;
          $greenChannel = (unpack('c*', $paletteBytes[$i+1])[1] & 0x3) << 6 | (unpack('c*', $paletteBytes[$i+0])[1] & 0xE0) >> 2;
          $blueChannel = (unpack('c*', $paletteBytes[$i+1])[1] & 0x7C) << 1;
          $blackFlag = (unpack('c*', $paletteBytes[$i+1])[1] & 0x80);
          if (($redChannel | $greenChannel | $blueChannel | $blackFlag) == 0) {
            // Transparent
            $palette[strval($colorCounter)] = imagecolorallocate($iconImage, 0, 0, 0);
            // imagecolortransparent($iconImage, $palette[strval($colorCounter)]);
            // imagesavealpha($iconImage, true);
          } else {
            $palette[strval($colorCounter)] = imagecolorallocate($iconImage, $redChannel, $greenChannel, $blueChannel);
          }
          $colorCounter = $colorCounter + 1;
        }     

        //Icon
        $unpack = unpack('C*', $value);

        $bytecount = $counter + 129;
        for ($i = 0; $i < 16; $i++) {
          for ($j = 0; $j < 16; $j += 2) {
            imagesetPixel($iconImage, $j, $i, $palette[$unpack[$bytecount] & 0xF]);
            imagesetPixel($iconImage, $j+1, $i,  $palette[$unpack[$bytecount] >> 4]);
            $bytecount++;
          }
        }

        ob_start(); 
        $rszImg = imagescale($iconImage, 48, 48);
        imagepng($rszImg);
        $png = ob_get_clean();
        $uri = "data:image/png;base64," . base64_encode($png);
        echo "<img src=" . $uri . " > ";

        if($blockCount[1] != 0) {
          $pathURL = "MemoryCards/" . $selectedGameId . "/" . $fileName;
          echo "Block ", $x+1, " : ";
          echo $convert, "\n";
          echo "\tSize : ", $blockCount[1], " Block(s)\n\n";
        }


        if ($blockCount[1] > 1) {
          for($i=1; $i <= $blockCount[1]-1; $i++) {
            echo "\n\n";
            echo "Block ", $i+1, " : ";
            echo $convert, "\n";
            echo "\tSize : ", $blockCount[1], " Block(s)\n\n";
          }
          $x = $blockCount[1]-1;
          $counter = $counter + (8192*intval($blockCount));
        }
      }
    }
    echo "\n\n";
  }
}

if(array_key_exists("refreshGameList", $_POST)) {
  printGameList($masterList);
}

if(array_key_exists("readGameCards", $_POST)) {
  $selectedGameId = $_POST["selectedGameId"];
  if($selectedGameId != "") {
    readGameCards($masterList, $selectedGameId);
  }
  else {
    echo "Error! No Game ID Selected.\n\n";
    printGameList($masterList);
  }  
}

?>
</pre>

</body>
</html>