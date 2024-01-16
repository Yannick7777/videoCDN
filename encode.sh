#!/bin/bash

ffmpeg -i $1 -c:v libvpx-vp9 -crf 30 -speed 6 -keyint_min 30 -g 60 $2

exit
