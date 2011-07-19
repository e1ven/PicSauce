<?php
$source_file = "test2.jpg";

// histogram options

$maxheight = 300;
$barwidth = 2;

$res = 10;

$im = imagecreatefromjpeg($source_file);

$imgw = imagesx($im);
$imgh = imagesy($im);

// n = total number or pixels

$n = $imgw*$imgh;
$histo = array();
for ($i=0; $i<$imgw; $i++)
{
        for ($j=0; $j<$imgh; $j++)
        {
       
                // get the rgb value for current pixel
               
                $rgb = ImageColorAt($im, $i, $j);
               
                // extract each value for r, g, b
               
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
		
		$results[round($i/$res)][round($j/$res)][0] += $r;
		$results[round($i/$res)][round($j/$res)][1] += $g;
		$results[round($i/$res)][round($j/$res)][2] += $b;

        }
}

$counter = 0;
for ($i=0;$i<($imgw/$res);$i++)
{
	for ($j=0;$j<($imgh/$res);$j++)
	{
		$counter++;
		$results_red_blue[$counter] = ($results[$i][$j][0] / $results[$i][$j][2]);
		$results_green_blue[$counter] = ($results[$i][$j][1] / $results[$i][$j][2]);
	}
}

asort($results_red_blue);
asort($results_green_blue);
foreach ($results_red_blue as $key => $val) {
    echo "$key";
    }
echo "<br>";
foreach ($results_green_blue as $key => $val) {
    echo "$key";
    }

exit;

// find the maximum in the histogram in order to display a normated graph

$max = 0;
for ($i=0; $i<255; $i++)
{
        if ($histo[$i] > $max)
        {
                $max = $histo[$i];
        }
}

echo "<div style='width: ".(256*$barwidth)."px; border: 1px solid'>";
for ($i=0; $i<255; $i++)
{
        $val += $histo[$i];
       
        $h = ( $histo[$i]/$max )*$maxheight;

        echo "<img src=\"img.gif\" width=\"".$barwidth."\"
height=\"".$h."\" border=\"0\">";
}
echo "</div>";
?> 
