# StyleCI Git ![Analytics](https://ga-beacon.appspot.com/UA-60053271-6/StyleCI/Git?pixel)


<a href="https://travis-ci.org/StyleCI/Git"><img src="https://img.shields.io/travis/StyleCI/Git/master.svg?style=flat-square" alt="Build Status"></img></a>
<a href="https://scrutinizer-ci.com/g/StyleCI/Git/code-structure"><img src="https://img.shields.io/scrutinizer/coverage/g/StyleCI/Git.svg?style=flat-square" alt="Coverage Status"></img></a>
<a href="https://scrutinizer-ci.com/g/StyleCI/Git"><img src="https://img.shields.io/scrutinizer/g/StyleCI/Git.svg?style=flat-square" alt="Quality Score"></img></a>
<a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square" alt="Software License"></img></a>
<a href="https://github.com/StyleCI/Git/releases"><img src="https://img.shields.io/github/release/StyleCI/Git.svg?style=flat-square" alt="Latest Version"></img></a>


## Installation

[PHP](https://php.net) 5.6+ and [Composer](https://getcomposer.org) are required.

To get the latest version of StyleCI Git, simply add the following line to the require block of your `composer.json` file:

```
"styleci/git": "~1.0"
```

You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

If you're using Laravel 5, then you can register our service provider. Open up `config/app.php` and add the following to the `providers` key.

* `'StyleCI\Git\GitServiceProvider'`

This will bind the repository factory to the ioc container. If you want the repository factory setup in a different way, then feel free to write and use your own service provider instead.


## Documentation

StyleCI Git is a git repository manager.

Feel free to check out the [releases](https://github.com/StyleCI/Git/releases), [license](LICENSE), and [contribution guidelines](CONTRIBUTING.md).


## License

StyleCI Git is licensed under [The MIT License (MIT)](LICENSE).
