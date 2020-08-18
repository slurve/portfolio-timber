# Slurvebox Theme

This theme is based off of the Timber Starter Theme.

# The Timber Starter Theme

The "\_s" for Timber: a dead-simple theme that you can build from. The primary purpose of this theme is to provide a file structure rather than a framework for markup or styles. Configure your Sass, scripts, and task runners however you would like!

[![Build Status](https://travis-ci.org/timber/starter-theme.svg)](https://travis-ci.org/timber/starter-theme)

## Setup

1. Navigate to the theme directory in Terminal and run `composer install`.
2. Then go ahead and run `npm install`.
3. Run `gulp` to make sure everything works. If so, you're good to go. Start building.

## What's here?

`assets/` is where you can keep your static front-end scripts, styles, or images. In other words, your Sass files, JS files, fonts, and SVGs would live here.

`templates/` contains all of your Twig templates. These pretty much correspond 1 to 1 with the PHP files that respond to the WordPress template hierarchy. At the end of each PHP template, you'll notice a `Timber::render()` function whose first parameter is the Twig file where that data (or `$context`) will be used. Just an FYI.

`bin/` and `tests/` ... basically don't worry about (or remove) these unless you know what they are and want to.

## Other Resources

The [main Timber Wiki](https://github.com/jarednova/timber/wiki) is super great, so reference those often. Also, check out these articles and projects for more info:

- [This branch](https://github.com/laras126/timber-starter-theme/tree/tackle-box) of the starter theme has some more example code with ACF and a slightly different set up.
- [Twig for Timber Cheatsheet](http://notlaura.com/the-twig-for-timber-cheatsheet/)
- [Timber and Twig Reignited My Love for WordPress](https://css-tricks.com/timber-and-twig-reignited-my-love-for-wordpress/) on CSS-Tricks
- [A real live Timber theme](https://github.com/laras126/yuling-theme).
- [Timber Video Tutorials](http://timber.github.io/timber/#video-tutorials) and [an incomplete set of screencasts](https://www.youtube.com/playlist?list=PLuIlodXmVQ6pkqWyR6mtQ5gQZ6BrnuFx-) for building a Timber theme from scratch.
