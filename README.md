# Spritemap Generator

Concatenates a series of PNG images into one for use with
FlashPunk and other game frameworks. Assumes all images
are the same width and height, and are already cropped.
Very simple (as you can see), but it works. Requires PHP
with GD extension.

## Usage:

    php -f spritegen.php

If you hit a memory limit error in PHP, run like this:

    php -dmemory_limit=512M -f spritegen.php

Author: John Luxford <lux@samsquanchgames.com>
Website: http://www.samsquanchgames.com/
License: http://opensource.org/licenses/lgpl-3.0
Updates: http://github.com/samsquanchgames/spritegen
