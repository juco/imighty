<?php

## 
## GDFont Renderer STYLE SHEET
## Version 2.0	
## Copyright 2008 Nick Schaffner
## http://53x11.com
##


/*
##		All variables are optional.
##		To create make more styles, copy/paste and rename the IF statement
##
##		If you have a working knowledge of PHP, the style possiblities are endless.
##		You could create mutilple styles that reference the same properties (e.g. if ($style == "heading" or "$style == "type"))
*/

if ($style == 'title') {
	
															// beyond this length, a new line will be started.
																// If left undefined or zero, the image will be whatever
																// length necessary to accommodate the text.
} 

if ($style == 'text') {
	
	$font 							= 'verdana';				// Font name, this should match a font.ttf filename (matching case)
	$size 							= 10;						// Size of your text in pixels
	$color 							= '#666699';				// Color in HEX format (#003300,FFF)
	$background 					= '#CCCC99';				// Background Color in HEX format
	$transparent					= true;						// FALSE will output the background color
	$alias							= true;						// TRUE will unsmooth the fonts
	$alignment 						= 'left';					// (left | center | right)
	$leading 						= 5; 						// Leading (line-height) in pixels
	$padding 						= 0;						// Total padding around all sides of the text box, in pixels	
	$vadj 							= -2;						// Adjust the final height of the text box in pixels (can be negative
	$hadj 							= 20;						// Adjust the final width of the text box in pixels (can be negative)
	$maxwidth 						= 0;						// Width of your text area in pixels, if text goes
																// beyond this length, a new line will be started.
																// If left undefined or zero, the image will be whatever
																// length necessary to accommodate the text.
}

if ($style == 'menu') {

	
	$font 							= 'GothamBook';				// Font name, this should match a font.ttf filename (matching case)
	$size 							= 8;						// Size of your text in pixels
	$color 							= '#afb6c0';					// Color in HEX format (#003300,FFF)
	$background 					= '#2d3236';					// Background Color in HEX format
	$transparent					= false;						// FALSE will output the background color
	$alias							= false;						// TRUE will unsmooth the fonts
	$alignment 						= 'left';					// (left | center | right)
	$leading 						= 5; 						// Leading (line-height) in pixels
	$padding 						= 0;						// Total padding around all sides of the text box, in pixels	
	$vadj 							= 0;						// Adjust the final height of the text box in pixels (can be negative
	$hadj 							= 1;						// Adjust the final width of the text box in pixels (can be negative)
	$maxwidth 						= 0;						// Width of your text area in pixels, if text goes
																// beyond this length, a new line will be started.
																// If left undefined or zero, the image will be whatever
																// length necessary to accommodate the text.
}
if ($style == 'menus_hover') {
	
	$font 							= 'GothamBook';				// Font name, this should match a font.ttf filename (matching case)
	$size 							= 8;						// Size of your text in pixels
	$color 							= '#fff';					// Color in HEX format (#003300,FFF)
	$background 					= '#2d3236';					// Background Color in HEX format
	$transparent					= false;						// FALSE will output the background color
	$alias							= false;						// TRUE will unsmooth the fonts
	$alignment 						= 'left';					// (left | center | right)
	$leading 						= 5; 						// Leading (line-height) in pixels
	$padding 						= 0;						// Total padding around all sides of the text box, in pixels	
	$vadj 							= 0;						// Adjust the final height of the text box in pixels (can be negative
	$hadj 							= 1;						// Adjust the final width of the text box in pixels (can be negative)
	$maxwidth 						= 0;						// Width of your text area in pixels, if text goes
																// beyond this length, a new line will be started.
																// If left undefined or zero, the image will be whatever
																// length necessary to accommodate the text.
}
if ($style == 'menus_1') {
	
	$font 							= 'GothaBol';				// Font name, this should match a font.ttf filename (matching case)
	$size 							= 8;						// Size of your text in pixels
	$color 							= '#fff';					// Color in HEX format (#003300,FFF)
	$background 					= '#2d3236';					// Background Color in HEX format
	$transparent					= false;						// FALSE will output the background color
	$alias							= false;						// TRUE will unsmooth the fonts
	$alignment 						= 'left';					// (left | center | right)
	$leading 						= 5; 						// Leading (line-height) in pixels
	$padding 						= 0;						// Total padding around all sides of the text box, in pixels	
	$vadj 							= 0;						// Adjust the final height of the text box in pixels (can be negative
	$hadj 							= 1;						// Adjust the final width of the text box in pixels (can be negative)
	$maxwidth 						= 0;						// Width of your text area in pixels, if text goes
																// beyond this length, a new line will be started.
																// If left undefined or zero, the image will be whatever
																// length necessary to accommodate the text.
}
if ($style == 'menus_2') {
	
	$font 							= 'GothaBoo';				// Font name, this should match a font.ttf filename (matching case)
	$size 							= 8;						// Size of your text in pixels
	$color 							= '#fff';					// Color in HEX format (#003300,FFF)
	$background 					= '#2d3236';					// Background Color in HEX format
	$transparent					= false;						// FALSE will output the background color
	$alias							= false;						// TRUE will unsmooth the fonts
	$alignment 						= 'left';					// (left | center | right)
	$leading 						= 5; 						// Leading (line-height) in pixels
	$padding 						= 0;						// Total padding around all sides of the text box, in pixels	
	$vadj 							= 0;						// Adjust the final height of the text box in pixels (can be negative
	$hadj 							= 1;						// Adjust the final width of the text box in pixels (can be negative)
	$maxwidth 						= 0;						// Width of your text area in pixels, if text goes
																// beyond this length, a new line will be started.
																// If left undefined or zero, the image will be whatever
																// length necessary to accommodate the text.
}
if ($style == 'titulong') { 
	$font 							= 'AvenirBlack2.ttf';				// Font name, this should match a font.ttf filename (matching case)
	$size 							= 10.5;						// Size of your text in pixels
	$color 							= '828397';					// Color in HEX format (#003300,FFF)
	$background 					= 'fff';					// Background Color in HEX format
	$transparent					= false;						// FALSE will output the background color
	$alias							= false;						// TRUE will unsmooth the fonts
	$alignment 						= 'left'; 					// (left | center | right)
	$leading 						= 7; 						// Leading (line-height) in pixels
	$padding 						= 0;						// Total padding around all sides of the text box, in pixels	
	$vadj 							= 10;						// Adjust the final height of the text box in pixels (can be negative
	$hadj 							= 1;	 					// Adjust the final width of the text box in pixels (can be negative)
	$maxwidth 						= 310;						// Width of your text area in pixels, if text goes
																// beyond this length, a new line will be started.
}
if ($style == 'textlink') {

	$font 							= 'AvenirBlack';				// Font name, this should match a font.ttf filename (matching case)
	$size 							= 9;						// Size of your text in pixels
	$color 							= '67686c';					// Color in HEX format (#003300,FFF)
	$background 					= '#fff';					// Background Color in HEX format
	$transparent					= false;						// FALSE will output the background color
	$alias							= false;						// TRUE will unsmooth the fonts
	$alignment 						= 'left';					// (left | center | right)
	$leading 						= 7; 						// Leading (line-height) in pixels
	$padding 						= 0;						// Total padding around all sides of the text box, in pixels	
	$vadj 							= 4;						// Adjust the final height of the text box in pixels (can be negative
	$hadj 							= 1;						// Adjust the final width of the text box in pixels (can be negative)
	$maxwidth 						= 0;						// Width of your text area in pixels, if text goes
																// beyond this length, a new line will be started.
}
if ($style == 'negrotextlink') {

	$font 							= 'AvenirBlack';				// Font name, this should match a font.ttf filename (matching case)
	$size 							= 9;						// Size of your text in pixels
	$color 							= 'cdced3';					// Color in HEX format (#003300,FFF)
	$background 					= '#42424a';					// Background Color in HEX format
	$transparent					= false;						// FALSE will output the background color
	$alias							= false;						// TRUE will unsmooth the fonts
	$alignment 						= 'left';					// (left | center | right)
	$leading 						= 7; 						// Leading (line-height) in pixels
	$padding 						= 0;						// Total padding around all sides of the text box, in pixels	
	$vadj 							= 4;						// Adjust the final height of the text box in pixels (can be negative
	$hadj 							= 1;						// Adjust the final width of the text box in pixels (can be negative)
	$maxwidth 						= 0;						// Width of your text area in pixels, if text goes
																// beyond this length, a new line will be started.
}
if ($style == 'titulongvideo') {

	$font 							= 'AvenirBlack';				// Font name, this should match a font.ttf filename (matching case)
	$size 							= 10.5;						// Size of your text in pixels
	$color 							= 'f0f1f5';					// Color in HEX format (#003300,FFF)
	$background 					= '#42424A';					// Background Color in HEX format
	$transparent					= false;						// FALSE will output the background color
	$alias							= false;						// TRUE will unsmooth the fonts
	$alignment 						= 'left';					// (left | center | right)
	$leading 						= 7; 						// Leading (line-height) in pixels
	$padding 						= 0;						// Total padding around all sides of the text box, in pixels	
	$vadj 							= 0;						// Adjust the final height of the text box in pixels (can be negative
	$hadj 							= 1;						// Adjust the final width of the text box in pixels (can be negative)
	$maxwidth 						= 310;						// Width of your text area in pixels, if text goes
																// beyond this length, a new line will be started.
}
if ($style == 'videolink') {

	$font 							= 'AvenirBlack';				// Font name, this should match a font.ttf filename (matching case)
	$size 							= 9.5;						// Size of your text in pixels
	$color 							= 'cdced3';					// Color in HEX format (#003300,FFF)
	$background 					= '#42424A';					// Background Color in HEX format
	$transparent					= false;						// FALSE will output the background color
	$alias							= false;						// TRUE will unsmooth the fonts
	$alignment 						= 'left';					// (left | center | right)
	$leading 						= 7; 						// Leading (line-height) in pixels
	$padding 						= 0;						// Total padding around all sides of the text box, in pixels	
	$vadj 							= 4;						// Adjust the final height of the text box in pixels (can be negative
	$hadj 							= 1;						// Adjust the final width of the text box in pixels (can be negative)
	$maxwidth 						= 0;						// Width of your text area in pixels, if text goes
																// beyond this length, a new line will be started.
}

if ($style == 'titulight') {
	$font 							= 'AvenirBlack';				// Font name, this should match a font.ttf filename (matching case)
	$size 							= 10.5;						// Size of your text in pixels
	$color 							= 'cdced3';					// Color in HEX format (#003300,FFF)
	$background 					= '#42424A';					// Background Color in HEX format
	$transparent					= false;						// FALSE will output the background color
	$alias							= false;						// TRUE will unsmooth the fonts
	$alignment 						= 'left';					// (left | center | right)
	$leading 						= 6; 						// Leading (line-height) in pixels
	$padding 						= 0;						// Total padding around all sides of the text box, in pixels	
	$vadj 							= 0;						// Adjust the final height of the text box in pixels (can be negative
	$hadj 							= 1;						// Adjust the final width of the text box in pixels (can be negative)
	$maxwidth 						= 0;						// Width of your text area in pixels, if text goes
																// beyond this length, a new line will be started.
}
if ($style == 'titulightflat') {
	$font 							= 'AvenirBlack';				// Font name, this should match a font.ttf filename (matching case)
	$size 							= 10.5;						// Size of your text in pixels
	$color 							= '767887';					// Color in HEX format (#003300,FFF)
	$background 					= '#42424A';					// Background Color in HEX format
	$transparent					= false;						// FALSE will output the background color
	$alias							= false;						// TRUE will unsmooth the fonts
	$alignment 						= 'left';					// (left | center | right)
	$leading 						= 6; 						// Leading (line-height) in pixels
	$padding 						= 0;						// Total padding around all sides of the text box, in pixels	
	$vadj 							= 0;						// Adjust the final height of the text box in pixels (can be negative
	$hadj 							= 1;						// Adjust the final width of the text box in pixels (can be negative)
	$maxwidth 						= 0;						// Width of your text area in pixels, if text goes
																// beyond this length, a new line will be started.
}
if ($style == 'sansation') {
	$font 							= 'Sansation';				// Font name, this should match a font.ttf filename (matching case)
	$size 							= 10.5;						// Size of your text in pixels
	$color 							= 'cdced3';					// Color in HEX format (#003300,FFF)
	$background 					= '181818';					// Background Color in HEX format
	$transparent					= false;						// FALSE will output the background color
	$alias							= false;						// TRUE will unsmooth the fonts
	$alignment 						= 'left';					// (left | center | right)
	$leading 						= 5; 						// Leading (line-height) in pixels
	$padding 						= 10;						// Total padding around all sides of the text box, in pixels	
	$vadj 							= 0;						// Adjust the final height of the text box in pixels (can be negative
	$hadj 							= 1;						// Adjust the final width of the text box in pixels (can be negative)
	$maxwidth 						= 0;						// Width of your text area in pixels, if text goes
																// beyond this length, a new line will be started.
}


?>