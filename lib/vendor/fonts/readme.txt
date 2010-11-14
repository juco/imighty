// GDFont Renderer
// Version 1.8
// Copyright 2005 Nick Schaffner
// http://53x11.com

Nevermore be shackled by the appearance of jagged fonts. Release the bonds of using the same tired web typefaces over and over again. Save yourself immeasurable hours of creating individual text images for titles. The time has come to bathe in the omnipotence of dynamically generated text images. By harnessing the power of PHP and the commonly built-in GD library, you too can become a god among men.

This super-lightweight script allows you to render any text into any TrueType font that is then displayed as an image on your webpage. It includes the option to cache any image created to reduce server load. 



1. First you need to make sure you have the GD library installed on your server. To test this, upload the file gd_php.php and run it. It must say “yes” for GD Support and GIF Create Support. If it doesn’t, you need to install the GD Library.



2. Open font.php with a text editor to configure the script variables.

Set caching to TRUE (recommended) if you wish to enable caching. If you enable caching, you must set the next variable to the path where you would like your images cached. This cache folder must have its permissions (CHMOD) set to 777.

Then set the path where you will be uploading your font files. Finally, set the path to font_style.php, the font stylesheet.



3. Open font_style.php to configure your font styles.

The only required variables for any style are, $font and $size. To create more than one style, copy everything between BEGIN and END style and paste it below the previous style. If you have a working knowledge of PHP you can customize these style sheets and create multiple styles that reference the same properties, for example: 

if ($style == "heading" or "$style == "text") { $color = “#666699”; }

Most of the variables are self-explanatory, but the final two, $vadj and $hadj require further information. The script makes a really good guess at the final outcome of what your fonts will look like and how much of them it needs to display. However, on certain fonts, it will add too much space below the last line of text – or sometimes cut-off the last letter. These “adjustment” varibles allow you compensate for the graphical mix-ups.


4. Upload font.php and font_style.php to your website.



5. Upload the fonts you will be using to the font path you declared earlier. The script only works with .ttf TrueType Fonts. Please note, that while most FTP clients determine this automatically, all font files need to be uploaded in Binary mode.



6. Now to turn any text into a rendered font, insert the following line of code in your HTML in-lieu of "Your Text": 

<img src=”font.php?text=Your Text Here&style=Title” />

Obviously “Your Text Here” is replaced with your text and “Title” is replaced with the name of the appropriate style as listed in your font_style.php file. You can also extend this code to include any of the variables listed in font_style.php, for instance: 

<img src=”font.php?text=Hello World&font=arial&size=15&color=#666699” />

Since the only required variables for any style are $font and $size you don’t need to list $style if those are included. You can use this inline method to create other styles without having to edit the font_style.php file.



7. The script includes an easy method to clear the image cache (which may be usefull depending on how often you render text). All you need to do is run font.php without any varibles. Bam, cache cleared.

This feature can also be run as a Cron Job. The following Cron Job is a sample and will clear your cache every 2 weeks.

0 3 14 * 0 /usr/local/bin/php public_html/font.php

You will need to alter the PHP location and root location of your font.php to match your server.



If you have a working knowledge of PHP or other server-side programming language (hell even client-side Javascript) the possibilities to dynamically generate text and styles are endless. Since you can cache any image created, you can implement this feature into many areas of your site without bogging down your server or download times. 

The only drawback to this system is that outputted text is no longer selectable. However, if you make sure to include detailed <alt> tags within all your images, the text should still be visible to imageless browsers and can be registered by search engines. 

So now that you hold the power of the universe (of TrueType fonts) in the palm of your hand (that is held by the power of that squishy palm-support thing for keyboards) – don’t forget those famous words Uncle Ben had to say to Peter Parker, “With great power, comes great poontang.” 

Tidbits of this script were blatantly stolen from Stewart Rosenberger’s Dynamic Text Replacement (http://www.alistapart.com/articles/dynatext/) script.


Version History
---------------

1.8	05/18/05 - Minor Script Optimization
1.7	04/18/05 - Added ability to align fonts and padding

1.5	04/17/05 - Rewrote most of the script, Added image cache, cache clear, better multiline engine, alpha support, auto-check GD Version, auto file format, HEX to RGB, font style sheets

1.0	04/14/05 - Basic text rendering functions, with a semi-retarded multiline engine