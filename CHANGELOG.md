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
