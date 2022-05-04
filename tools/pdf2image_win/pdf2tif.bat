pdf2img.exe -r 300 -compress 4 -gray -threshold 180 -multipage test3.pdf _test3_no_halftone.tif
pdf2img.exe -r 300 -compress 4 -mono -multipage test3.pdf _test3_halftone.tif
pdf2img.exe -r 300 -compress 4 -gray -threshold 180 test_skew.pdf _test_skew.tif

