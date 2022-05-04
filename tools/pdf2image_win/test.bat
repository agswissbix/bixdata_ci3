pdf2img.exe test.pdf test.png
pdf2img.exe test.pdf test.tif
pdf2img.exe -compress 88881 -mono -multipage test.pdf test-204x196-G4.tif
pdf2img.exe -compress 1 test.pdf test-none.tif
pdf2img.exe -compress 2 -mono test.pdf test-rle.tif
pdf2img.exe -compress 3 -mono test.pdf test-g3.tif
pdf2img.exe -compress 4 -mono test.pdf test-g4.tif
pdf2img.exe -compress 5 test.pdf test-lzw.tif
pdf2img.exe -compress 7 test.pdf test-jpeg.tif
pdf2img.exe -compress 32773 test.pdf test-packbits.tif
pdf2img.exe -compress 88880 test.pdf test-204x98-g4.tif
pdf2img.exe -compress 88881 test.pdf test-204x196-g4.tif
pdf2img.exe -compress 88882 test.pdf test-204x98-g3.tif
pdf2img.exe -compress 88883 test.pdf test-204x196-g3.tif
pdf2img.exe test.pdf test.jpg
pdf2img.exe -dither 1 -compress 88881 test.pdf test-dither-yes.tif
pdf2img.exe -dither 0 -compress 88881 test.pdf test-dither-no.tif
pdf2img.exe -trimimage test.pdf test-trimimage.png

pause
