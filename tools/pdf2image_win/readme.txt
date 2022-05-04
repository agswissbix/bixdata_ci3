VeryPDF PDF to Image Converter Command Line Version 5.10
Convert PDF files to Image files, include TIFF, JPEG, GIF, PNG, BMP, EMF, PCX, TGA. etc.
Copyright 1996-2016 VeryPDF.com Inc.
Web: http://www.verypdf.com/
Email: support@verypdf.com
Build Date: Aug  1 2016
Support raster image formats:
  1. BMP: Microsoft Windows bitmap
  2. GIF: CompuServe Graphics Interchange Format
  3. JPG: Joint Photographic Experts Group JFIF format
  4. PNG: Portable Network Graphics
  5. TGA: Truevision Targa image
  6. PCX: ZSoft IBM PC Paintbrush file
  7. PNM: Portable anymap
  8. RAS: SUN Raster Format
  9. PBM: Portable bitmap format (black and white)
  10. TIF: Tagged Image File Format
Usage: pdf2img.exe [options] <PDF-file> <img-file>
  -f <int>                  : first page to print
  -l <int>                  : last page to print
  -r <int>                  : resolution for both X and Y, in DPI (default is 150)
  -xres <int>               : xresolution, in DPI (default is 150)
  -yres <int>               : yresolution, in DPI (default is 150)
  -w <int>                  : set image width, unit is pixel
  -width <int>              : set image width, unit is pixel, same as -w
  -h <int>                  : set image height, unit is pixel
  -height <int>             : set image height, unit is pixel, same as -h
  -mono                     : generate a monochrome image file
  -gray                     : generate a grayscale image file
  -errordiffusion <int>     : enable or disable Error Diffusion when reduce the number of bits per pixel
  -dither <int>             : convert the color image to B&W using the desired method:
    -dither 0: Floyd-Steinberg
    -dither 1: Ordered-Dithering (4x4), Floyd & Steinberg error diffusion
    -dither 2: Burkes, BAYER4x4, Bayer dot dithering (order 2 dithering matrix)
    -dither 3: Stucki, BAYER8x8, Bayer dot dithering (order 3 dithering matrix)
    -dither 4: Jarvis-Judice-Ninke, CLUSTER6x6, Clustered dot dithering (order 3 - 6x6 matrix)
    -dither 5: Sierra, CLUSTER8x8, Clustered dot dithering (order 4 - 8x8 matrix)
    -dither 6: Stevenson-Arce, CLUSTER16x16, Clustered dot dithering (order 8 - 16x16 matrix)
    -dither 7: BAYER16x16, Bayer dot dithering (order 4 dithering matrix)
  -dithermode <string>      : set the mode of Dithering Arithmetic, default is 8888 for FineDithering
  -color24to8               : generate a grayscale image file
  -compress <int>           : set compression to TIFF format:
   -compress 1     : NONE compression
   -compress 2     : CCITT modified Huffman RLE
   -compress 3     : CCITT Group 3 fax encoding (1d)
   -compress 4     : CCITT Group 4 fax encoding
   -compress 5     : LZW compression
   -compress 6     : OJPEG compression
   -compress 7     : JPEG DCT compression
   -compress 32773 : PACKBITS compression
   -compress 32809 : THUNDERSCAN compression
   -compress 32946 : DEFLATE compression
   -compress 88880 : 204x98, G4, Width=1728, Height=auto, ClassF TIFF
   -compress 88881 : 204x196,G4, Width=1728, Height=auto, ClassF TIFF
   -compress 88882 : 204x98, G3, Width=1728, Height=auto, ClassF TIFF
   -compress 88883 : 204x196,G3, Width=1728, Height=auto, ClassF TIFF
   -compress 88884 : CCITT Group 3 fax encoding (2d)
   -compress 88888886 : 204x98, G3, Width=1728, Height=auto, ClassF TIFF
   -compress 88888887 : 204x196,G3, Width=1728, Height=auto, ClassF TIFF
   -compress 88888888 : 204x98, G4, Width=1728, Height=auto, ClassF TIFF
   -compress 88888889 : 204x196,G4, Width=1728, Height=auto, ClassF TIFF
   -compress 88888890 : 204x98  2048x1401 G4 ClassF TIFF
   -compress 88888891 : 204x196 2048x2802 G4 ClassF TIFF
   -compress 88888892 : 204x98  1620x1146 G4 ClassF TIFF
   -compress 88888893 : 204x98  1728x1143 G4 ClassF TIFF
   -compress 88888894 : 204x196 1728x2286 G4 ClassF TIFF
   -compress 88888895 : 204x98  1728x1074 G4 ClassF TIFF
   -compress 88888896 : 204x98  2200x1700 G4 ClassF TIFF
   -compress 88888897 : 204x98  1728x2200 G4 ClassF TIFF
   -compress 88888898 : 204x196 1728x2200 G4 ClassF TIFF
   -compress 88888899 : 204x196 1728x1100 G4 ClassF TIFF
   -compress 88888900 : 204x98  1728x1100 G4 ClassF TIFF
  -quality <int>            : set quality to JPEG format, from 0 to 100
  -multipage                : create multipage TIFF file
  -aa <string>              : enable font anti-aliasing: yes/no, default is 'yes'
  -aavec <string>           : enable vector anti-aliasing: yes/no, default is 'yes'
  -aaVector <string>        : enable vector anti-aliasing: yes/no, same as -aavec
  -aaimg <string>           : enable image anti-aliasing: yes/no, default is 'yes'
  -opw <string>             : owner password (for encrypted files)
  -upw <string>             : user password (for encrypted files)
  -trimimage                : trim image file
  -forcebwtif               : force to create black and white TIFF files
  -listfiles                : list converted files to screen
  -threshold <int>          : the lightness threshold that used to convert image to B&W
  -forcexdpi <int>          : force to set X DPI to image file
  -forceydpi <int>          : force to set Y DPI to image file
  -checkhiddentext          : extract images that do not have underlying text
  -rotate <int>             : rotate output image file at special angle
  -render2                  : render PDF page to image files by second method
  -tempname <string>        : set filename template for output image files
  -tempname1                : apply filename template for single image file
  -suffix                   : set first filename to img0001.jpg format
  -debug                    : output debug information
  -sharedmemoryname <string>: shared memory name
  -$ <string>               : input your license key
  -h                        : print usage information
  -help                     : print usage information
  --help                    : print usage information
  -?                        : print usage information
Example:
   pdf2img.exe C:\in.pdf C:\out.tif
   pdf2img.exe C:\in.pdf C:\out.gif
   pdf2img.exe C:\in.pdf C:\out.jpg
   pdf2img.exe C:\in.pdf C:\out.bmp
   pdf2img.exe C:\in.pdf C:\out.png
   pdf2img.exe -f 1 -l 10 -r 300 -mono C:\in.pdf C:\out.tif
   pdf2img.exe -compress 88881 -mono C:\in.pdf C:\out.tif
   pdf2img.exe -compress 88881 -mono -multipage C:\in.pdf C:\out.tif
   pdf2img.exe -trimimage C:\in.pdf C:\out.png
   pdf2img.exe -opw 123 -upw 456 -aa no C:\in.pdf C:\out.png
   for %F in (D:\temp\*.pdf) do pdf2img.exe "%F" "%~dpnF.png"
   for /r D:\temp %F in (*.pdf) do pdf2img.exe "%F" "%~dpnF.png"
   for %F in (D:\temp\*.pdf) do pdf2img.exe "%F" "%~F.png"
   pdf2img.exe -dither 1 -mono C:\in.pdf C:\out.tif
   pdf2img.exe -dither 0 -mono C:\in.pdf C:\out.tif
   pdf2img.exe -xres 300 -yres 300 D:\in.pdf D:\out.png
   pdf2img.exe -xres 600 -yres 600 -mono D:\in.pdf D:\out.tif
   pdf2img.exe -width 1024 -height 768 D:\in.pdf D:\out.tif
   pdf2img.exe -gray D:\in.pdf D:\out.tif
   pdf2img.exe -compress 4 -mono D:\in.pdf D:\out.tif
   pdf2img.exe -compress 5 -mono D:\in.pdf D:\out.tif
   pdf2img.exe -compress 32773 D:\in.pdf D:\out.tif
   pdf2img.exe -quality D:\in.pdf D:\out.jpg
   pdf2img.exe -multipage D:\in.pdf D:\out.tif
   pdf2img.exe -aa yes -aavec yes D:\in.pdf D:\out.jpg
   pdf2img.exe -forcebwtif D:\in.pdf D:\out.tif
   pdf2img.exe -threshold 240 -forcexdpi 204 -forceydpi 98 D:\in.pdf D:\out.tif
   pdf2img.exe -threshold 240 D:\in.pdf D:\out.png
   pdf2img.exe -tempname out_%04d -tempname1 D:\in.pdf D:\out.tif
   pdf2img.exe -rotate 90 D:\in.pdf D:\out.jpg
   pdf2img.exe -$ XXXXXXXXXXXXXXXX
   pdf2img.exe -checkhiddentext -mono C:\in.pdf C:\out.tif
   pdf2img.exe -w 1728 -h 2286 -multipage -gray -compress 88888894 -dithermode smooth-fine C:\in.pdf C:\out.tif
   pdf2img.exe -w 1728 -h 1143 -multipage -gray -compress 88888893 -dithermode smooth-fine C:\in.pdf C:\out.tif
   pdf2img.exe -w 1728 -h 2286 -multipage -gray -compress 88888894 -dithermode smooth-normal C:\in.pdf C:\out.tif
   pdf2img.exe -w 1728 -h 2286 -multipage -gray -compress 88888894 -dithermode rough-fine C:\in.pdf C:\out.tif
   pdf2img.exe -w 1728 -h 2286 -multipage -gray -compress 88888894 -dithermode rough-normal C:\in.pdf C:\out.tif

