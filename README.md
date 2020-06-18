# PackageFactory.ColorHelper
## EEL Color Helper, implementing some of the less color tranformations

This internally uses a copy of mexitek/phpcolors to do the conversions
between hex, rgb and hsl color representations.

## Usage

The package provides the `Color` helper that exposes the following methods to Fusion.

Colors can be created from hex, rgb and hsl values
- `color = ${ Color.hex('#80e619') }`  expects a hex string of 3 or 6 chars
- `color = ${ Color.rgb(100, 0, 256) }` expects three integers each between 0 and 255
- `color = ${ Color.hsl(156, 25, 75) }` expects three integers a degree 0-355 and two percent values 0-100 

Once created those colors can then be manipulated via fluent interface 

Adjust saturation
- `color = ${ Color.hex('#80e619').saturate(20) }` >> #80ff00
- `color = ${ Color.hex('#80e619').desaturate( 20) }` >> #80cd33

Adjust lightness
- `color = ${ Color.hex('#80e619').lighten('#80e619', 20) }` >> #b3f075
- `color = ${ Color.hex('#80e619').darken('#80e619', 20) }` >> #4d8a0f

Modify the color value by rotating in the hue axis  
- `color = ${ Color.hex('#f2330d').spin(30) }` >> #f2a20d
- `color = ${ Color.hex('#f2330d').spin(-30) }` >> #f20d59

Invert color
- `color = ${ Color.hex('#f2330d').spin(180) }` >> #0dd0f2

Mix colors
- `color = ${ Color.hex('#ff0000').mix(Color.hex('#0000ff'), 50) }` >> #800080

Offcourse this can be usd in afx attributes as any other eel expression.

## Installation

PackageFactory.ColorHelper is available via packagist. Run `composer require packagefactory/colorhelper`.
We use semantic-versioning so every breaking change will increase the major-version number.

## Contribution

We will gladly accept contributions. Please send us pull requests.
