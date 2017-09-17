Ntentan Config
==============

[![Build Status](https://travis-ci.org/ntentan/config.png)](https://travis-ci.org/ntentan/config) 
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ntentan/config/badges/quality-score.png)](https://scrutinizer-ci.com/g/ntentan/config/)
[![Code Coverage](https://scrutinizer-ci.com/g/ntentan/config/badges/coverage.png)](https://scrutinizer-ci.com/g/ntentan/config/)
[![Latest Stable Version](https://poser.pugx.org/ntentan/config/version.svg)](https://packagist.org/packages/ntentan/config)
[![Total Downloads](https://poser.pugx.org/ntentan/config/downloads)](https://packagist.org/packages/ntentan/config)

Provides a central service through which components of an application can manage
their configurations. Config can read single files and can also read entire directories. When reading directories, config compresses the entire tree into
a single key-value associative array with the directory structure compressed into
dot-separated keys.