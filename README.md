# PackageFactory.ColorHelper
## EEL Color Helper, implementing a fluent interface for color transformations

The package provides the `Color` helper that exposes the following methods to Fusion.

### Creating

Colors can be created from hex, rgb and hsl values
- `color = ${ Color.hex('#80e619') }`  expects a hex string of 3 or 6 chars
- `color = ${ Color.rgb(100, 0, 255) }` expects three integers each between 0 and 255
- `color = ${ Color.hsl(156, 25, 75) }` expects three integers a degree 0-355 and two percent values 0-100 

The methods rgb and hsl allow to specify the alpha as fourth argument 
expecting a float between 0 and 1 `color = ${ Color.hsl(156, 25, 75, 0.5) }`

If you have a color value specified as css color string you can use the
`Color.css` method to instantiate the color. Plaese be aware that this
uses a very simple regex based parser for the css colors and for now only 
suppprts hex,rgb and hsla colors.   

- `color = ${ Color.css('#80e619') }`
- `color = ${ Color.css('rbg( 10%, 50%, 0%, 50%)') }`
- `color = ${ Color.css('hsl( 270, 10%, 50%, 0.5)') }`

### Manipulating 

Once created those colors can then be manipulated via fluent interface
like a flow query for colors. 

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

Fade colors
- `color = ${ Color.hex('#f2330d').fadeout(10) }` >> #0dd0f2
- `color = ${ Color.rgb(255,0,0,0).fadein(20) }` >> #0dd0f2

Mix colors
- `color = ${ Color.hex('#ff0000').mix(Color.hex('#0000ff'), 50) }` >> #800080

Offcourse this can be used in afx attributes as any other eel expression.

### Value rendering

When casted to string the color objects will render as hex value. 
For special requirements the format can be specified. All formats will 
only render an alpha value if the color is transparent. 

- `hex = ${ Color.rgb(255,0,0).hex() }` >> #ff0000
- `rgb = ${ Color.rgba(255,0,0).fadeout(50).rgb() }` >> rgba( 255, 0, 0, 0.5)
- `hsl = ${ Color.rgba(255,0,0).hsl() }` >> hsla( 0, 100%, 50%)

## Installation

PackageFactory.ColorHelper is available via packagist. Run `composer require packagefactory/colorhelper`.
We use semantic-versioning so every breaking change will increase the major-version number.

## Contribution

We will gladly accept contributions. Please send us pull requests.
