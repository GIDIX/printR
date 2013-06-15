# What is printR?

**printR** is THE better `print_r`. How often do you need to know what the contents of a specific variable are? Right: often.
Unfortunately PHP's `print_r` displays it's content only readable when you view the source code in your browser. It also is
not styled so that it get's confusing the more content a variable has. printR solves all these problems. You can even
disable it so that you don't need to remove every single call if you need it often.

## 1. INSTALL

Just copy the file to where you want to use it. Include it via

`include 'printR.class.php';` (or `printR.compact.php` if you need `printR()`)

and you are ready to go.


## 2. USAGE

Use printR just as you would use print_r(). You can pass any type of variable into it. Remember: Call
	
`echo printR::styles();`

once before you use it. Is is necessary for printR's design.


## 3. CALLS

`printR::show($variable)`
-- Better print_r.

`printR::disable()`
-- Disable output of printR-Functions.

`printR::enable()`
-- Enable output of printR-Functions.

`printR::styles()`
-- Returns printR's stylesheet. Use echo for displaying it.

`printR::$parseJSON`
-- Set to false to prevent automatical parsing of JSON. (default = true)

`printR::is_assoc($array)`
-- Returns true if passed array is associative.

### PRO TIP

If you need to call printR very fast, just call `printR($var)`. It will automatically include the styles and display
the contens of the passed variable. (Only available if you use `printR.compact.php`) ;)


## 4. LICENSE

Copyright (c) 2013 gidix.de. You can use it in whatever way you want. You are free to make modifications. Distributing
modifications anywhere or the original on sites other than gidix.de, labs.gidix.de, bluefirex.de and
bluefirex' GitHub and BitBucket is prohibited.