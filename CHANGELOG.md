## 1.0.0 (2015-12-31)

No changes. The current API has held up for quite sometime and is being used in production by a number of folks. Therefore, Tactician now has official 1.0 support. A huge thanks to everyone who's contributed over the last year!

## 0.6.1 (2015-08-02)

BC Breaks:
- None

New features:
- [A CallableLocator added, allowing you to retrieve a handler from a single callable](). Hopefully this can be used as an alternative to creating all the different Container plugins.
- Officially support PHP7 now

Bug fixes:
- [LockingMiddleware no longer permanently locks the command bus on error](https://github.com/thephpleague/tactician/issues/75)

## 0.6.0 (2015-05-08)

BC Breaks:
- None

New features:

- [Added CONTRIBUTING.md](https://github.com/thephpleague/tactician/pull/57)
- [Middlewares are now checked for the correct interface](https://github.com/thephpleague/tactician/pull/56) 

Bug fixes:

- [LockingMiddleware now passes the correct command](https://github.com/thephpleague/tactician/pull/62)

## 0.5.0 (2015-03-30)

tl;dr CommandHandlerMiddleware now requires an extra first parameter, you just need to pass an instance of `ClassNameExtractor` to it.  

BC breaks:
Previously, the `HandlerLocator` interface was responsible for both mapping a Command to a string name AND looking up that string name in some sort of DI container or locator. That worked alright but made it difficult to use custom naming strategies with different DI containers. Therefore, we've made the following two changes:

- A new interface, `CommandNameExtractor` is responsible for mapping a Command to a string name.
- `HandlerLocator::getHandlerForCommand()` now accepts a string name instead of a Command object as its only parameter.
- The `CommandHandlerMiddleware` now requires a `CommandNameExtractor` as its first parameter. To continue using the same behavior you've had until now, you only need to pass in an instance of `ClassNameExtractor`

New features:

- A new MethodNameInflector is included with core, `HandleClassNameWithoutSuffix`. Since many users suffix their command class names with `-Command`, this allows you to have Handler methods based on the class name but without that Suffix. In other words, the command class `RentMovieCommand` would be mapped to the method `handleRentMovie`. 

Bug fixes:

 - Several docblocks have been corrected or improved.

## 0.4.0 (2015-03-30)
BC breaks:

- Removed the `League\Tactician\Command` interface. Now any plain ol' PHP object can be a command! See #43 and #45.

New features:

- None

Bug fixes:

- None

## 0.3.0 (2015-02-15)
First public release!
