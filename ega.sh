#!/bin/bash
# (requires Image Magick)
echo 'P3 8 8 3' >ega.ppm
for r in {0..3}
do
  for g in {0..3}
    do
        for b in {0..3}
	    do
	          echo $r $g $b >>ega.ppm
		      done
		        done
			done
			convert ega.ppm ega.png

