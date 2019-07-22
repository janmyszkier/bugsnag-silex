# Bugsnag exception reporter for Silex
[![Build Status](https://img.shields.io/travis/bugsnag/bugsnag-silex/master.svg?style=flat-square)](https://travis-ci.org/bugsnag/bugsnag-silex)
[![StyleCI Status](https://styleci.io/repos/23802949/shield?branch=master)](https://styleci.io/repos/23802949)
[![Documentation](https://img.shields.io/badge/documentation-latest-blue.svg?style=flat-square)](https://docs.bugsnag.com/platforms/php/)

The Bugsnag Notifier for Silex gives you instant notification of errors and exceptions in your Silex PHP applications. We support both Silex 1 and 2. Learn more about [monitoring and reporting errors in your Silex PHP apps](https://www.bugsnag.com/platforms/php/silex/) with Bugsnag.


## Features

* Automatically report unhandled exceptions and crashes
* Report handled exceptions
* Attach user information and custom diagnostic data to determine how many people are affected by a crash


## Getting started

1. [Create a Bugsnag account](https://www.bugsnag.com)
2. Complete the instructions in the [integration guide](https://docs.bugsnag.com/platforms/php/silex/)
3. Report handled exceptions using [`Bugsnag::notify()`](https://docs.bugsnag.com/platforms/php/silex/#reporting-handled-exceptions)
4. Customize your integration using the [configuration options](https://docs.bugsnag.com/platforms/php/silex/configuration-options/)


## Support

* Check out the [configuration options](https://docs.bugsnag.com/platforms/php/silex/configuration-options/)
* [Search open and closed issues](https://github.com/bugsnag/bugsnag-silex/issues?utf8=✓&q=is%3Aissue) for similar problems
* [Report a bug or request a feature](https://github.com/bugsnag/bugsnag-silex/issues/new)


## Contributing

All contributors are welcome! For information on how to build, test, and release, see our [contributing guide](CONTRIBUTING.md).


## License

The Bugsnag Silex library is free software released under the MIT License. See [LICENSE.txt](LICENSE.txt) for details.

### add to Spryker
Open \Pyz\Yves\ShopApplication\YvesBootstrap::registerServiceProviders and add at the end
```
        $this->application->set('bugsnag.options',[
            'api_key' => '<YOUR-API-KEY>',
        ]);
        $this->application->error(
            function (\Exception $error) {
              $this->application->set('bugsnag.notifier',[$error]);
            }
        );

        //test your integration
        //$this->application['bugsnag']->notifyException(new \RuntimeException("Test Yves error"));
```
Open 
\Pyz\Zed\Application\Communication\ZedBootstrap::setUp and add as the end
```
$this->application->register(new Silex1ServiceProvider());

        $this->application->set('bugsnag.options',[
            'api_key' => '<YOUR-API-KEY>',
        ]);
        $this->application->error(
            function (\Exception $error) {
                $this->application->set('bugsnag.notifier',[$error]);
            }
        );
        $this->application['bugsnag']->notifyException(new \RuntimeException("Test zed error"));
```

        
